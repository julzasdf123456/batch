<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStudentScholarshipsRequest;
use App\Http\Requests\UpdateStudentScholarshipsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\StudentScholarshipsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Payables;
use App\Models\Scholarships;
use App\Models\StudentScholarships;
use App\Models\IDGenerator;
use App\Models\TuitionsBreakdown;
use App\Models\PayableInclusions;
use Flash;

class StudentScholarshipsController extends AppBaseController
{
    /** @var StudentScholarshipsRepository $studentScholarshipsRepository*/
    private $studentScholarshipsRepository;

    public function __construct(StudentScholarshipsRepository $studentScholarshipsRepo)
    {
        $this->middleware('auth');
        $this->studentScholarshipsRepository = $studentScholarshipsRepo;
    }

    /**
     * Display a listing of the StudentScholarships.
     */
    public function index(Request $request)
    {
        $studentScholarships = $this->studentScholarshipsRepository->paginate(10);

        return view('student_scholarships.index')
            ->with('studentScholarships', $studentScholarships);
    }

    /**
     * Show the form for creating a new StudentScholarships.
     */
    public function create()
    {
        return view('student_scholarships.create');
    }

    /**
     * Store a newly created StudentScholarships in storage.
     */
    public function store(CreateStudentScholarshipsRequest $request)
    {
        $input = $request->all();

        $studentScholarships = $this->studentScholarshipsRepository->create($input);

        Flash::success('Student Scholarships saved successfully.');

        return redirect(route('studentScholarships.index'));
    }

    /**
     * Display the specified StudentScholarships.
     */
    public function show($id)
    {
        $studentScholarships = $this->studentScholarshipsRepository->find($id);

        if (empty($studentScholarships)) {
            Flash::error('Student Scholarships not found');

            return redirect(route('studentScholarships.index'));
        }

        return view('student_scholarships.show')->with('studentScholarships', $studentScholarships);
    }

    /**
     * Show the form for editing the specified StudentScholarships.
     */
    public function edit($id)
    {
        $studentScholarships = $this->studentScholarshipsRepository->find($id);

        if (empty($studentScholarships)) {
            Flash::error('Student Scholarships not found');

            return redirect(route('studentScholarships.index'));
        }

        return view('student_scholarships.edit')->with('studentScholarships', $studentScholarships);
    }

    /**
     * Update the specified StudentScholarships in storage.
     */
    public function update($id, UpdateStudentScholarshipsRequest $request)
    {
        $studentScholarships = $this->studentScholarshipsRepository->find($id);

        if (empty($studentScholarships)) {
            Flash::error('Student Scholarships not found');

            return redirect(route('studentScholarships.index'));
        }

        $studentScholarships = $this->studentScholarshipsRepository->update($request->all(), $id);

        Flash::success('Student Scholarships updated successfully.');

        return redirect(route('studentScholarships.index'));
    }

    /**
     * Remove the specified StudentScholarships from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $studentScholarships = $this->studentScholarshipsRepository->find($id);

        if (empty($studentScholarships)) {
            Flash::error('Student Scholarships not found');

            return redirect(route('studentScholarships.index'));
        }

        $this->studentScholarshipsRepository->delete($id);

        Flash::success('Student Scholarships deleted successfully.');

        return redirect(route('studentScholarships.index'));
    }

    public function scholarshipWizzard($id, $from) {
        return view('/student_scholarships/scholarship_wizzard', [
            'id' => $id,
            'from' => $from,
        ]);
    }

    public function getAvailableSYPayables(Request $request) {
        $studentId = $request['StudentId'];

        $data = DB::table('Payables')
            ->whereRaw("StudentId='" . $studentId . "' AND Category='Tuition Fees' AND Balance > 0")
            ->select('*')
            ->orderByDesc('created_at')
            ->get();

        foreach($data as $item) {
            $item->TuitionInclusions = PayableInclusions::where('PayableId', $item->id)->orderBy('ItemName')->get();
        }

        return response()->json($data, 200);
    }

    public function getGrants(Request $request) {
        return response()->json(Scholarships::orderBy('Scholarship')->get(), 200);
    }

    public function applyScholarship(Request $request) {
        $payableId = $request['PayableId'];
        $studentId = $request['StudentId'];
        $schoolYear = $request['SchoolYear'];
        $scholarshipId = $request['ScholarshipId'];
        $amount = $request['Amount'];
        $deductMonthly = $request['DeductMonthly'];

        // save scholarship profile
        $scholarship = new StudentScholarships;
        $scholarship->id = IDGenerator::generateIDandRandString();
        $scholarship->PayableId = $payableId;
        $scholarship->SchoolYear = $schoolYear;
        $scholarship->ScholarshipId = $scholarshipId;
        $scholarship->Amount = $amount;
        $scholarship->StudentId = $studentId;
        $scholarship->DeductMonthly = $deductMonthly;
        $scholarship->save();

        // update payable
        if ($deductMonthly === 'Yes') {
            $payable = Payables::find($payableId);

            if ($payable != null) {
                $payable->DiscountAmount = $amount;
                $payable->AmountPayable = floatval($payable->AmountPayable) - floatval($amount);
                $payable->Balance = floatval($payable->Balance) - floatval($amount);
                $payable->save();
            }
            
            // update payable tuitions breakdown
            $tuitionsBreakdown = TuitionsBreakdown::where('PayableId', $payableId)->whereRaw("AmountPaid IS NULL OR AmountPaid = 0")->get();
            if ($tuitionsBreakdown != null) {
                $count = count($tuitionsBreakdown);

                if ($count > 0) {
                    $amountDistributable = round((floatval($amount) / $count), 2);
                
                    foreach($tuitionsBreakdown as $item) {
                        $item->Discount = $amountDistributable;
                        $item->AmountPayable = floatval($item->AmountPayable) - floatval($amountDistributable);
                        $item->Balance = floatval($item->Balance) - floatval($amountDistributable);
                        $item->save();
                    }
                }
            }
        }

        return response()->json($scholarship, 200);
    }

    public function removeScholarship(Request $request) {
        $id = $request['id'];

        $scholarship = StudentScholarships::find($id);

        if ($scholarship != null) {
            if ($scholarship->DeductMonthly === 'Yes') {
                $grantAmount = floatval($scholarship->Amount);

                // update payable
                $payable = Payables::find($scholarship->PayableId);

                if ($payable != null) {
                    $payable->DiscountAmount = null;
                    $payable->Balance = floatval($payable->Balance) + floatval($grantAmount);
                    $payable->AmountPayable = floatval($payable->Payable);
                    $payable->save();
                }

                // update payable tuitions breakdown
                $tuitionsBreakdown = TuitionsBreakdown::where('PayableId', $scholarship->PayableId)->whereRaw("Discount IS NOT NULL AND (AmountPaid IS NULL OR AmountPaid = 0)")->get();
                if ($tuitionsBreakdown != null) {
                    foreach($tuitionsBreakdown as $item) {
                        $item->Discount = null;
                        $item->AmountPayable = $item->Payable;
                        $item->Balance = $item->Payable;
                        $item->save();
                    }
                }
            }

            $scholarship->delete();
        }

        return response()->json($scholarship, 200);
    }
}
