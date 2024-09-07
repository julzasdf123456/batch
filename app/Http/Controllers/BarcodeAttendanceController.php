<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBarcodeAttendanceRequest;
use App\Http\Requests\UpdateBarcodeAttendanceRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BarcodeAttendanceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use Illuminate\Support\Facades\URL;
use App\Models\BarcodeAttendance;
use App\Models\IDGenerator;
use App\Models\SmsMessages;
use App\Models\Students;
use DateTime;
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

        $to = date('Y-m-d H:i:s');
        $from = date('Y-m-d H:i:s', strtotime($to . " -5 hours"));

        $bCodeCheck = DB::table('BarcodeAttendance')
            ->whereRaw("StudentId='" . $input['StudentId'] . "' AND created_at BETWEEN '" . $from . "' AND '" . $to . "'")
            ->first();

        if ($bCodeCheck != null) {
            return response()->json('Student has already logged ' . $input['PunchType'], 400);
        } else {
            $barcodeAttendance = $this->barcodeAttendanceRepository->create($input);

            // save sms for sending
            if (isset($input['ContactNumber']) && $input['ContactNumber'] != null && strlen($input['ContactNumber']) >= 10) {
                //get student
                $student = Students::find($input['StudentId']);

                if ($student != null) {
                    SmsMessages::create([
                        'id' => IDGenerator::generateIDandRandString(),
                        'ContactNumber' => $input['ContactNumber'],
                        'Message' => env("APP_COMPANY") . " System Notification\n\n" .
                            $student->FirstName . " " . $student->LastName . " has logged " . $input['PunchType'] . " of/to the campus at " . date('D, M d, Y h:i A'),
                        'AIFacilitator' => 'Reeve',
                        'Source' => 'batch.ID',
                        'Priority' => 1,
                    ]);
                }
            }

            return response()->json($barcodeAttendance, 200);
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
        date_default_timezone_set('Asia/Manila');

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
        // $data = DB::table('BarcodeAttendance')
        //     ->leftJoin('Students', 'BarcodeAttendance.StudentId', '=', 'Students.id')
        //     ->whereRaw("SmsSent IS NULL AND BarcodeAttendance.created_at >= '" . date('Y-m-d') . "' AND BarcodeAttendance.ContactNumber IS NOT NULL")
        //     ->select(
        //         'BarcodeAttendance.*',
        //         DB::raw("(CONCATenv("APP_COMPANY") . (Holy Cross Academy System Notification\n\n', Students.FirstName, ' ', Students.LastName, ' has logged ', BarcodeAttendance.PunchType, ' from/to the campus at ', BarcodeAttendance.created_at)) As Message"),
        //         DB::raw("'batch.ID' AS AIFacilitator"),
        //         DB::raw("'batch.ID' AS Source"),
        //     )
        //     ->orderBy('BarcodeAttendance.created_at')
        //     ->first();

        $data = SmsMessages::whereRaw('SmsSent IS NULL AND Message IS NOT NULL AND ContactNumber IS NOT NULL AND LEN(ContactNumber) > 9')
            ->orderBy('Priority')
            ->orderBy('created_at')
            ->first();

        return response()->json($data, 200);
    }

    public function updateSMS(Request $request) {
        $id = $request['id'];
        $status = $request['Status'];

        SmsMessages::where('id', $id)
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

    public function getBarcodeAttendancePerClass(Request $request) {
        $classId = $request['ClassId'];

        $data = DB::table('BarcodeAttendance')
            ->whereRaw("StudentId IN (SELECT s.id FROM StudentClasses sc LEFT JOIN Students s ON s.id=sc.StudentId WHERE sc.ClassId='" . $classId . "')")
            ->get();

        return response()->json($data, 200);
    }

    public function downloadSF2Junior($classId, $month, $year) {
        // get class data
        $class = DB::table('Classes')
            ->leftJoin('SchoolYear', 'Classes.SchoolYearId', '=', 'SchoolYear.id')
            ->select(
                'Classes.*',
                'SchoolYear.SchoolYear'
            )
            ->whereRaw("Classes.id='" . $classId . "'")
            ->first();

        //get attendance data
        $attendanceData = DB::table('BarcodeAttendance')
            ->whereRaw("StudentId IN (SELECT s.id FROM StudentClasses sc LEFT JOIN Students s ON s.id=sc.StudentId WHERE sc.ClassId='" . $classId . "')")
            ->get()
            ->toArray();

        // get male students
        $male = DB::table('StudentClasses')
            ->leftJoin('Students', 'StudentClasses.StudentId', '=', 'Students.id')
            ->whereRaw("StudentClasses.ClassId='" . $classId . "' AND Gender='Male'")
            ->select(
                'Students.*',
            )
            ->orderBy('Students.LastName')
            ->get();

        $female = DB::table('StudentClasses')
            ->leftJoin('Students', 'StudentClasses.StudentId', '=', 'Students.id')
            ->whereRaw("StudentClasses.ClassId='" . $classId . "' AND Gender='Female'")
            ->select(
                'Students.*',
            )
            ->orderBy('Students.LastName')
            ->get();

        $crossOut = [
            'borders' => [
                'diagonal' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
                'diagonalDirection' => \PhpOffice\PhpSpreadsheet\Style\Borders::DIAGONAL_BOTH,
            ],
        ];

        $crossOutHalf = [
            'borders' => [
                'diagonal' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
                'diagonalDirection' => \PhpOffice\PhpSpreadsheet\Style\Borders::DIAGONAL_UP,
            ],
        ];

        $diagonalColor = [
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 45, // Diagonal gradient
                'startColor' => [
                    'argb' => '02c480', // Green
                ],
                'endColor' => [
                    'argb' => 'FFFFFF', // White
                ],
            ],
            'font' => [
                'color' => [
                    'argb' => '00000000', // Same color as the background to simulate transparency
                ],
            ],
        ];
        
        /**
         * MODIFY EXCEL
         */
        $filePath = public_path('templates/SF2_Junior.xls');
        $spreadsheet = IOFactory::load($filePath);

        // Access the first worksheet
        $worksheet = $spreadsheet->getActiveSheet();

        // Modify the worksheet
        // HEADERS
        $monthSpelled = date('F Y', strtotime($year . '-' . $month . '-01'));
        $worksheet->setCellValue('N3', $class != null ? ($class->SchoolYear != null ? $class->SchoolYear : '-') : '-');
        $worksheet->setCellValue('AD3', $monthSpelled);
        $worksheet->setCellValue('AD4', $class != null ? ($class->Year != null ? $class->Year : '-') : '-');
        $worksheet->setCellValue('AR4', $class != null ? ($class->Section != null ? $class->Section : '-') : '-');
        
        $worksheet->setCellValue('F5', date('F', strtotime($year . '-' . $month . '-01')));

        
        // attendance headers
        $totalMonthDays = date('d', strtotime('last day of ' . $monthSpelled));
        $startOfDay = date('N', strtotime('first day of ' . $monthSpelled));

        $startOfDay = intval($startOfDay);
        $totalMonthDays = intval($totalMonthDays);

        $headerDates = BarcodeAttendance::sf2JuniorDateHeaderColumnArray();
        $headerIndices = ($startOfDay - 1);
        for ($i=0; $i<$totalMonthDays; $i++) {
            // skip if sunday
            if (date('D', strtotime($year . '-' . $month . '-' . ($i+1))) === 'Sun') {

            } else {
                $worksheet->setCellValue($headerDates[$headerIndices], ($i + 1));
                $headerIndices += 1;
            }
        }

        // list of students
        $morningThresholdTime = env('STUDENT_IN_AM_THRESHOLD');
        $afternoonThresholdTime = env('STUDENT_OUT_PM_THRESHOLD');
        /*
         * ==================================
         * MALE
         * ==================================
         */
        $indexStart = 8;
        foreach($male as $item) {
            $worksheet->setCellValue('C' . $indexStart, $item->LastName . ', ' . $item->FirstName . ' ' . $item->MiddleName);

            // filter attendance data
            if ($attendanceData != null) {
                $headerDates = BarcodeAttendance::sf2JuniorDateHeaderColumnArrayNoRowNum();
                $headerIndices = ($startOfDay - 1);

                $totalPresent = 0;
                $totalAbsent = 0;
                $totalDays = 0;
                for ($i=0; $i<$totalMonthDays; $i++) {
                    // skip if sunday
                    if (date('D', strtotime($year . '-' . $month . '-' . ($i+1))) === 'Sun') {
        
                    } else {
                        $currentDate = date('Y-m-d', strtotime($year . '-' . $month . '-' . ($i+1)));
                        $attData = BarcodeAttendance::getAttendanceProfileFromStudentAndDate($attendanceData, $item->id, $currentDate);

                        if ($attData != null && count($attData) > 0) {
                            // return 0.5 or 1 if there is attendance
                            // validate morning in first
                            $morningInThreshold = new DateTime($currentDate . ' ' . $morningThresholdTime);
                            $morningData = null;
                            foreach($attData as $itemAtt) {
                                $morningIn = new DateTime($itemAtt->created_at);

                                if ($morningIn <= $morningInThreshold) {
                                    if ($morningData == null) {
                                        $morningData = $itemAtt->created_at;
                                    }
                                }
                            }
                            
                            // validate afternoon out
                            $afternoonInThreshold = new DateTime($currentDate . ' ' . $afternoonThresholdTime);
                            $afternoonData = null;
                            foreach($attData as $itemAtt) {
                                $afternoonIn = new DateTime($itemAtt->created_at);

                                if ($afternoonIn >= $afternoonInThreshold) {
                                    if ($afternoonData == null) {
                                        $afternoonData = $itemAtt->created_at;
                                    }
                                }
                            }

                            // validate total attendance
                            $attSum = 0;
                            if ($morningData != null) {
                                $attSum += 0.5;
                            }

                            if ($afternoonData != null) {
                                $attSum += 0.5;
                            }

                            $totalPresent += $attSum;

                            if ($attSum == 1) {

                            } else {
                                $worksheet->getStyle($headerDates[$headerIndices] . $indexStart)->applyFromArray($diagonalColor);
                                $worksheet->getStyle($headerDates[$headerIndices] . $indexStart)->applyFromArray($crossOutHalf);
                            }

                            $worksheet->setCellValue($headerDates[$headerIndices] . $indexStart, $attSum);
                        } else {
                            //return 0 if no attendance
                            // $worksheet->setCellValue($headerDates[$headerIndices] . $indexStart, 0);
                            $worksheet->getStyle($headerDates[$headerIndices] . $indexStart)->applyFromArray($crossOut);
                        }

                        $headerIndices += 1;
                    }

                    $totalDays += 1;
                }

                // set total present
                $worksheet->setCellValue('AT' . $indexStart, $totalPresent);

                // set total absent
                $totalAbsent = $totalDays - $totalPresent;
                $worksheet->setCellValue('AR' . $indexStart, $totalAbsent);
            }

            $indexStart += 1;
        }

        // male total
        $headerDates = BarcodeAttendance::sf2JuniorDateHeaderColumnArrayNoRowNum();
        $headerIndices = ($startOfDay - 1);

        $totalPresent = 0;
        $totalAbsent = 0;
        $totalDays = 0;
        for ($i=0; $i<$totalMonthDays; $i++) {
            // skip if sunday
            if (date('D', strtotime($year . '-' . $month . '-' . ($i+1))) === 'Sun') {

            } else {
                $worksheet->setCellValue($headerDates[$headerIndices] . '38', '=SUM(' . $headerDates[$headerIndices] . '8:' . $headerDates[$headerIndices] . '37)');

                $headerIndices += 1;
            }
        }

        /*
         * ==================================
         * FEMALE
         * ==================================
         */
        $indexStart = 39;
        foreach($female as $item) {
            $worksheet->setCellValue('C' . $indexStart, $item->LastName . ', ' . $item->FirstName . ' ' . $item->MiddleName);

            // filter attendance data
            if ($attendanceData != null) {
                $headerDates = BarcodeAttendance::sf2JuniorDateHeaderColumnArrayNoRowNum();
                $headerIndices = ($startOfDay - 1);

                $totalPresent = 0;
                $totalAbsent = 0;
                $totalDays = 0;
                for ($i=0; $i<$totalMonthDays; $i++) {
                    // skip if sunday
                    if (date('D', strtotime($year . '-' . $month . '-' . ($i+1))) === 'Sun') {
        
                    } else {
                        $currentDate = date('Y-m-d', strtotime($year . '-' . $month . '-' . ($i+1)));
                        $attData = BarcodeAttendance::getAttendanceProfileFromStudentAndDate($attendanceData, $item->id, $currentDate);

                        if ($attData != null && count($attData) > 0) {
                            // return 0.5 or 1 if there is attendance
                            // validate morning in first
                            $morningInThreshold = new DateTime($currentDate . ' ' . $morningThresholdTime);
                            $morningData = null;
                            foreach($attData as $itemAtt) {
                                $morningIn = new DateTime($itemAtt->created_at);

                                if ($morningIn <= $morningInThreshold) {
                                    if ($morningData == null) {
                                        $morningData = $itemAtt->created_at;
                                    }
                                }
                            }
                            
                            // validate afternoon out
                            $afternoonInThreshold = new DateTime($currentDate . ' ' . $afternoonThresholdTime);
                            $afternoonData = null;
                            foreach($attData as $itemAtt) {
                                $afternoonIn = new DateTime($itemAtt->created_at);

                                if ($afternoonIn >= $afternoonInThreshold) {
                                    if ($afternoonData == null) {
                                        $afternoonData = $itemAtt->created_at;
                                    }
                                }
                            }

                            // validate total attendance
                            $attSum = 0;
                            if ($morningData != null) {
                                $attSum += 0.5;
                            }

                            if ($afternoonData != null) {
                                $attSum += 0.5;
                            }

                            $totalPresent += $attSum;

                            if ($attSum == 1) {

                            } else {
                                $worksheet->getStyle($headerDates[$headerIndices] . $indexStart)->applyFromArray($diagonalColor);
                                $worksheet->getStyle($headerDates[$headerIndices] . $indexStart)->applyFromArray($crossOutHalf);
                            }

                            $worksheet->setCellValue($headerDates[$headerIndices] . $indexStart, $attSum);
                        } else {
                            //return 0 if no attendance
                            // $worksheet->setCellValue($headerDates[$headerIndices] . $indexStart, 0);
                            $worksheet->getStyle($headerDates[$headerIndices] . $indexStart)->applyFromArray($crossOut);
                        }

                        $headerIndices += 1;
                    }

                    $totalDays += 1;
                }

                // set total present
                $worksheet->setCellValue('AT' . $indexStart, $totalPresent);

                // set total absent
                $totalAbsent = $totalDays - $totalPresent;
                $worksheet->setCellValue('AR' . $indexStart, $totalAbsent);
            }

            $indexStart += 1;
        }

        // female and combined total
        $headerDates = BarcodeAttendance::sf2JuniorDateHeaderColumnArrayNoRowNum();
        $headerIndices = ($startOfDay - 1);

        $totalPresent = 0;
        $totalAbsent = 0;
        $totalDays = 0;
        for ($i=0; $i<$totalMonthDays; $i++) {
            // skip if sunday
            if (date('D', strtotime($year . '-' . $month . '-' . ($i+1))) === 'Sun') {

            } else {
                // female total
                $worksheet->setCellValue($headerDates[$headerIndices] . '67', '=SUM(' . $headerDates[$headerIndices] . '39:' . $headerDates[$headerIndices] . '66)');

                // combined total
                $worksheet->setCellValue($headerDates[$headerIndices] . '68', '=SUM(' . $headerDates[$headerIndices] . '67,' . $headerDates[$headerIndices] . '38)');

                $headerIndices += 1;
            }
        }

        // Save the modified file
        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('generated/sf2/SF2_Junior.xls'));

        return response()->download(public_path('generated/sf2/SF2_Junior.xls'));
    }

    public function downloadSF2Senior($classId, $month, $year) {
        // get class data
        $class = DB::table('Classes')
            ->leftJoin('SchoolYear', 'Classes.SchoolYearId', '=', 'SchoolYear.id')
            ->select(
                'Classes.*',
                'SchoolYear.SchoolYear'
            )
            ->whereRaw("Classes.id='" . $classId . "'")
            ->first();

        //get attendance data
        $attendanceData = DB::table('BarcodeAttendance')
            ->whereRaw("StudentId IN (SELECT s.id FROM StudentClasses sc LEFT JOIN Students s ON s.id=sc.StudentId WHERE sc.ClassId='" . $classId . "')")
            ->get()
            ->toArray();

        // get male students
        $male = DB::table('StudentClasses')
            ->leftJoin('Students', 'StudentClasses.StudentId', '=', 'Students.id')
            ->whereRaw("StudentClasses.ClassId='" . $classId . "' AND Gender='Male'")
            ->select(
                'Students.*',
            )
            ->orderBy('Students.LastName')
            ->get();

        $female = DB::table('StudentClasses')
            ->leftJoin('Students', 'StudentClasses.StudentId', '=', 'Students.id')
            ->whereRaw("StudentClasses.ClassId='" . $classId . "' AND Gender='Female'")
            ->select(
                'Students.*',
            )
            ->orderBy('Students.LastName')
            ->get();

        $crossOut = [
            'borders' => [
                'diagonal' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
                'diagonalDirection' => \PhpOffice\PhpSpreadsheet\Style\Borders::DIAGONAL_BOTH,
            ],
        ];

        $crossOutHalf = [
            'borders' => [
                'diagonal' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
                'diagonalDirection' => \PhpOffice\PhpSpreadsheet\Style\Borders::DIAGONAL_UP,
            ],
        ];

        $diagonalColor = [
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 45, // Diagonal gradient
                'startColor' => [
                    'argb' => '02c480', // Green
                ],
                'endColor' => [
                    'argb' => 'FFFFFF', // White
                ],
            ],
            'font' => [
                'color' => [
                    'argb' => '00000000', // Same color as the background to simulate transparency
                ],
            ],
        ];

        /**
         * MODIFY EXCEL
         */
        $filePath = public_path('templates/SF2_Senior.xls');
        $spreadsheet = IOFactory::load($filePath);

        // Access the first worksheet
        $worksheet = $spreadsheet->getActiveSheet();


        // Modify the worksheet
        // HEADERS
        $monthSpelled = date('F Y', strtotime($year . '-' . $month . '-01'));
        $worksheet->setCellValue('V8', $class != null ? ($class->SchoolYear != null ? $class->SchoolYear : '-') : '-');
        $worksheet->setCellValue('K15', $monthSpelled);
        $worksheet->setCellValue('AQ8', $class != null ? ($class->Year != null ? $class->Year : '-') : '-');
        $worksheet->setCellValue('I12', $class != null ? ($class->Section != null ? $class->Section : '-') : '-');
        $worksheet->setCellValue('BG7', $class != null ? ($class->Strand != null ? $class->Strand : '-') : '-');
        
        $worksheet->setCellValue('BS11', date('F', strtotime($year . '-' . $month . '-01')));

        // attendance headers
        $totalMonthDays = date('d', strtotime('last day of ' . $monthSpelled));
        $startOfDay = date('N', strtotime('first day of ' . $monthSpelled));

        $startOfDay = intval($startOfDay);
        $totalMonthDays = intval($totalMonthDays);

        $headerDates = BarcodeAttendance::sf2SeniorDateHeaderColumnArray();
        $headerIndices = ($startOfDay - 1);
        for ($i=0; $i<$totalMonthDays; $i++) {
            // skip if sunday
            if (date('D', strtotime($year . '-' . $month . '-' . ($i+1))) === 'Sun') {

            } else {
                $worksheet->setCellValue($headerDates[$headerIndices], ($i + 1));
                $headerIndices += 1;
            }
        }

        // list of students
        $morningThresholdTime = env('STUDENT_IN_AM_THRESHOLD');
        $afternoonThresholdTime = env('STUDENT_OUT_PM_THRESHOLD');

        /*
         * ==================================
         * MALE
         * ==================================
         */
        $indexStart = 18;
        foreach($male as $item) {
            $worksheet->setCellValue('G' . $indexStart, $item->LastName . ', ' . $item->FirstName . ' ' . $item->MiddleName);

            // filter attendance data
            if ($attendanceData != null) {
                $headerDates = BarcodeAttendance::sf2SeniorDateHeaderColumnArrayNoRowNum();
                $headerIndices = ($startOfDay - 1);

                $totalPresent = 0;
                $totalAbsent = 0;
                $totalDays = 0;
                for ($i=0; $i<$totalMonthDays; $i++) {
                    // skip if sunday
                    if (date('D', strtotime($year . '-' . $month . '-' . ($i+1))) === 'Sun') {
        
                    } else {
                        $currentDate = date('Y-m-d', strtotime($year . '-' . $month . '-' . ($i+1)));
                        $attData = BarcodeAttendance::getAttendanceProfileFromStudentAndDate($attendanceData, $item->id, $currentDate);

                        if ($attData != null && count($attData) > 0) {
                            // return 0.5 or 1 if there is attendance
                            // validate morning in first
                            $morningInThreshold = new DateTime($currentDate . ' ' . $morningThresholdTime);
                            $morningData = null;
                            foreach($attData as $itemAtt) {
                                $morningIn = new DateTime($itemAtt->created_at);

                                if ($morningIn <= $morningInThreshold) {
                                    if ($morningData == null) {
                                        $morningData = $itemAtt->created_at;
                                    }
                                }
                            }
                            
                            // validate afternoon out
                            $afternoonInThreshold = new DateTime($currentDate . ' ' . $afternoonThresholdTime);
                            $afternoonData = null;
                            foreach($attData as $itemAtt) {
                                $afternoonIn = new DateTime($itemAtt->created_at);

                                if ($afternoonIn >= $afternoonInThreshold) {
                                    if ($afternoonData == null) {
                                        $afternoonData = $itemAtt->created_at;
                                    }
                                }
                            }

                            // validate total attendance
                            $attSum = 0;
                            if ($morningData != null) {
                                $attSum += 0.5;
                            }

                            if ($afternoonData != null) {
                                $attSum += 0.5;
                            }

                            $totalPresent += $attSum;

                            $worksheet->setCellValue($headerDates[$headerIndices] . $indexStart, $attSum);
                            
                            if ($attSum == 1) {

                            } else {
                                $worksheet->getStyle($headerDates[$headerIndices] . $indexStart)->applyFromArray($diagonalColor);
                                $worksheet->getStyle($headerDates[$headerIndices] . $indexStart)->applyFromArray($crossOutHalf);
                            }

                        } else {
                            //return 0 if no attendance
                            // $worksheet->setCellValue($headerDates[$headerIndices] . $indexStart, 0);

                            $worksheet->getStyle($headerDates[$headerIndices] . $indexStart)->applyFromArray($crossOut);
                        }

                        $headerIndices += 1;
                    }

                    $totalDays += 1;
                }

                // set total present
                $worksheet->setCellValue('BQ' . $indexStart, $totalPresent);

                // set total absent
                $totalAbsent = $totalDays - $totalPresent;
                $worksheet->setCellValue('BN' . $indexStart, $totalAbsent);
            }

            $indexStart += 1;
        }

        // male total
        $headerDates = BarcodeAttendance::sf2SeniorDateHeaderColumnArrayNoRowNum();
        $headerIndices = ($startOfDay - 1);

        $totalPresent = 0;
        $totalAbsent = 0;
        $totalDays = 0;
        for ($i=0; $i<$totalMonthDays; $i++) {
            // skip if sunday
            if (date('D', strtotime($year . '-' . $month . '-' . ($i+1))) === 'Sun') {

            } else {
                $worksheet->setCellValue($headerDates[$headerIndices] . '51', '=SUM(' . $headerDates[$headerIndices] . '18:' . $headerDates[$headerIndices] . '50)');

                $headerIndices += 1;
            }
        }

        /*
         * ==================================
         * FEMALE
         * ==================================
         */
        $indexStart = 52;
        foreach($female as $item) {
            $worksheet->setCellValue('G' . $indexStart, $item->LastName . ', ' . $item->FirstName . ' ' . $item->MiddleName);

            // filter attendance data
            if ($attendanceData != null) {
                $headerDates = BarcodeAttendance::sf2SeniorDateHeaderColumnArrayNoRowNum();
                $headerIndices = ($startOfDay - 1);

                $totalPresent = 0;
                $totalAbsent = 0;
                $totalDays = 0;
                for ($i=0; $i<$totalMonthDays; $i++) {
                    // skip if sunday
                    if (date('D', strtotime($year . '-' . $month . '-' . ($i+1))) === 'Sun') {
        
                    } else {
                        $currentDate = date('Y-m-d', strtotime($year . '-' . $month . '-' . ($i+1)));
                        $attData = BarcodeAttendance::getAttendanceProfileFromStudentAndDate($attendanceData, $item->id, $currentDate);

                        if ($attData != null && count($attData) > 0) {
                            // return 0.5 or 1 if there is attendance
                            // validate morning in first
                            $morningInThreshold = new DateTime($currentDate . ' ' . $morningThresholdTime);
                            $morningData = null;
                            foreach($attData as $itemAtt) {
                                $morningIn = new DateTime($itemAtt->created_at);

                                if ($morningIn <= $morningInThreshold) {
                                    if ($morningData == null) {
                                        $morningData = $itemAtt->created_at;
                                    }
                                }
                            }
                            
                            // validate afternoon out
                            $afternoonInThreshold = new DateTime($currentDate . ' ' . $afternoonThresholdTime);
                            $afternoonData = null;
                            foreach($attData as $itemAtt) {
                                $afternoonIn = new DateTime($itemAtt->created_at);

                                if ($afternoonIn >= $afternoonInThreshold) {
                                    if ($afternoonData == null) {
                                        $afternoonData = $itemAtt->created_at;
                                    }
                                }
                            }

                            // validate total attendance
                            $attSum = 0;
                            if ($morningData != null) {
                                $attSum += 0.5;
                            }

                            if ($afternoonData != null) {
                                $attSum += 0.5;
                            }

                            $totalPresent += $attSum;

                            if ($attSum == 1) {

                            } else {
                                $worksheet->getStyle($headerDates[$headerIndices] . $indexStart)->applyFromArray($diagonalColor);
                                $worksheet->getStyle($headerDates[$headerIndices] . $indexStart)->applyFromArray($crossOutHalf);
                            }

                            $worksheet->setCellValue($headerDates[$headerIndices] . $indexStart, $attSum);
                        } else {
                            //return 0 if no attendance
                            // $worksheet->setCellValue($headerDates[$headerIndices] . $indexStart, 0);

                            $worksheet->getStyle($headerDates[$headerIndices] . $indexStart)->applyFromArray($crossOut);
                        }

                        $headerIndices += 1;
                    }

                    $totalDays += 1;
                }

                // set total present
                $worksheet->setCellValue('BQ' . $indexStart, $totalPresent);

                // set total absent
                $totalAbsent = $totalDays - $totalPresent;
                $worksheet->setCellValue('BN' . $indexStart, $totalAbsent);
            }

            $indexStart += 1;
        }

        // female total
        $headerDates = BarcodeAttendance::sf2SeniorDateHeaderColumnArrayNoRowNum();
        $headerIndices = ($startOfDay - 1);

        $totalPresent = 0;
        $totalAbsent = 0;
        $totalDays = 0;
        for ($i=0; $i<$totalMonthDays; $i++) {
            // skip if sunday
            if (date('D', strtotime($year . '-' . $month . '-' . ($i+1))) === 'Sun') {

            } else {
                // female total
                $worksheet->setCellValue($headerDates[$headerIndices] . '83', '=SUM(' . $headerDates[$headerIndices] . '52:' . $headerDates[$headerIndices] . '82)');

                // combined total
                $worksheet->setCellValue($headerDates[$headerIndices] . '84', '=SUM(' . $headerDates[$headerIndices] . '51,' . $headerDates[$headerIndices] . '83)');

                $headerIndices += 1;
            }
        }

        // Save the modified file
        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('generated/sf2/SF2_Senior.xls'));

        return response()->download(public_path('generated/sf2/SF2_Senior.xls'));
    }
}

