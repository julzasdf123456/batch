<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBarcodeAttendanceRequest;
use App\Http\Requests\UpdateBarcodeAttendanceRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BarcodeAttendanceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BarcodeAttendance;
use Flash;

class BarcodeAttendanceController extends AppBaseController
{
    /** @var BarcodeAttendanceRepository $barcodeAttendanceRepository*/
    private $barcodeAttendanceRepository;

    public function __construct(BarcodeAttendanceRepository $barcodeAttendanceRepo)
    {
        $this->barcodeAttendanceRepository = $barcodeAttendanceRepo;
    }

    /**
     * Display a listing of the BarcodeAttendance.
     */
    public function index(Request $request)
    {
        $barcodeAttendances = $this->barcodeAttendanceRepository->paginate(10);

        return view('barcode_attendances.index')
            ->with('barcodeAttendances', $barcodeAttendances);
    }

    /**
     * Show the form for creating a new BarcodeAttendance.
     */
    public function create()
    {
        return view('barcode_attendances.create');
    }

    /**
     * Store a newly created BarcodeAttendance in storage.
     */
    public function store(CreateBarcodeAttendanceRequest $request)
    {
        $input = $request->all();

        if (isset($input['ContactNumber']) && $input['ContactNumber'] != null && strlen($input['ContactNumber']) >= 10) {
            $barcodeAttendance = $this->barcodeAttendanceRepository->create($input);

            // Flash::success('Barcode Attendance saved successfully.');
    
            // return redirect(route('barcodeAttendances.index'));
            return response()->json($barcodeAttendance, 200);
        } else {
            return response()->json('Invalid Contact Number', 200);
        }
    }

    /**
     * Display the specified BarcodeAttendance.
     */
    public function show($id)
    {
        $barcodeAttendance = $this->barcodeAttendanceRepository->find($id);

        if (empty($barcodeAttendance)) {
            Flash::error('Barcode Attendance not found');

            return redirect(route('barcodeAttendances.index'));
        }

        return view('barcode_attendances.show')->with('barcodeAttendance', $barcodeAttendance);
    }

    /**
     * Show the form for editing the specified BarcodeAttendance.
     */
    public function edit($id)
    {
        $barcodeAttendance = $this->barcodeAttendanceRepository->find($id);

        if (empty($barcodeAttendance)) {
            Flash::error('Barcode Attendance not found');

            return redirect(route('barcodeAttendances.index'));
        }

        return view('barcode_attendances.edit')->with('barcodeAttendance', $barcodeAttendance);
    }

    /**
     * Update the specified BarcodeAttendance in storage.
     */
    public function update($id, UpdateBarcodeAttendanceRequest $request)
    {
        $barcodeAttendance = $this->barcodeAttendanceRepository->find($id);

        if (empty($barcodeAttendance)) {
            Flash::error('Barcode Attendance not found');

            return redirect(route('barcodeAttendances.index'));
        }

        $barcodeAttendance = $this->barcodeAttendanceRepository->update($request->all(), $id);

        Flash::success('Barcode Attendance updated successfully.');

        return redirect(route('barcodeAttendances.index'));
    }

    /**
     * Remove the specified BarcodeAttendance from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $barcodeAttendance = $this->barcodeAttendanceRepository->find($id);

        if (empty($barcodeAttendance)) {
            Flash::error('Barcode Attendance not found');

            return redirect(route('barcodeAttendances.index'));
        }

        $this->barcodeAttendanceRepository->delete($id);

        Flash::success('Barcode Attendance deleted successfully.');

        return redirect(route('barcodeAttendances.index'));
    }

    public function punchStudent(Request $request) {
        $id = $request['StudentId'];
        
        $student = DB::table('Students')
            ->leftJoin('Towns', 'Students.Town', '=', 'Towns.id')
            ->leftJoin('Barangays', 'Students.Barangay', '=', 'Barangays.id')
            ->leftJoin('Classes', 'Students.CurrentGradeLevel', '=', 'Classes.id')
            ->whereRaw("Students.id='" . $id . "'")
            ->select('Students.*',
                'Towns.Town AS TownSpelled',
                'Barangays.Barangay AS BarangaySpelled',
                'Classes.Year',
                'Classes.Section',
            )
            ->first();

        $latestPunch = DB::table('BarcodeAttendance')
            ->whereRaw("StudentId='" . $id . "'")
            ->orderByDesc('created_at')
            ->first();

        $data = [
            'StudentDetails' => $student,
            'LatestPunch' => $latestPunch,
        ];

        return response()->json($data, 200);
    }

    public function getSMSQueue(Request $request) {
        // only fetch todays sms
        $data = DB::table('BarcodeAttendance')
            ->leftJoin('Students', 'BarcodeAttendance.StudentId', '=', 'Students.id')
            ->whereRaw("SmsSent IS NULL AND BarcodeAttendance.created_at >= '" . date('Y-m-d') . "' AND BarcodeAttendance.ContactNumber IS NOT NULL")
            ->select(
                'BarcodeAttendance.*',
                DB::raw("(CONCAT('Holy Cross Academy System Notification\n\n', Students.FirstName, ' ', Students.LastName, ' has logged ', BarcodeAttendance.PunchType, ' from/to the campus at ', BarcodeAttendance.created_at)) As Message"),
                DB::raw("'batch.ID' AS AIFacilitator"),
                DB::raw("'batch.ID' AS Source"),
            )
            ->orderBy('BarcodeAttendance.created_at')
            ->first();

        return response()->json($data, 200);
    }

    public function updateSMS(Request $request) {
        $id = $request['id'];
        $status = $request['Status'];

        BarcodeAttendance::where('id', $id)
            ->update(['SmsSent' => $status]);

        return response()->json('ok', 200);
    }

    public function getStudentLogs(Request $request) {
        $studentId = $request['StudentId'];

        return response()->json(DB::table('BarcodeAttendance')
            ->whereRaw("StudentId='" . $studentId . "'")
            ->select('PunchType', 'created_at')
            ->orderByDesc('created_at')
            ->get(),
            
            200);
    }
}
