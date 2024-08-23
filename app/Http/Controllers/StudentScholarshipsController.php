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
use App\Models\Students;
use App\Models\SchoolYear;
use App\Models\StudentScholarships;
use App\Models\IDGenerator;
use App\Models\TuitionsBreakdown;
use App\Models\PayableInclusions;
use App\Models\Classes;
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
        if (Auth::user()->hasAnyPermission(['god permission', 'add scholarship grant'])) {
            return view('/student_scholarships/scholarship_wizzard', [
                'id' => $id,
                'from' => $from,
            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
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
        $id = IDGenerator::generateIDandRandString();
        $scholarship = new StudentScholarships;
        $scholarship->id = $id;
        $scholarship->PayableId = $payableId;
        $scholarship->SchoolYear = $schoolYear;
        $scholarship->ScholarshipId = $scholarshipId;
        $scholarship->Amount = $amount;
        $scholarship->StudentId = $studentId;
        $scholarship->DeductMonthly = $deductMonthly;
        $scholarship->save();

        $sy = SchoolYear::where('SchoolYear', $schoolYear)->first();

        // update student if esc scholar
        $scholar = Scholarships::find($scholarshipId);
        if ($scholar != null) {
            if ($scholar->Scholarship === 'ESC' | $scholar->Scholarship === 'VMS (Public)' | $scholar->Scholarship === 'VMS (Private)') {
                Students::where('id', $studentId)
                    ->update(['ESCScholar' => 'Yes']);
            }
        }

        // update payable
        if ($deductMonthly === 'Yes') {
            $payable = Payables::find($payableId);

            $student = Students::find($studentId);
            $class = Classes::find($student != null && $student->CurrentGradeLevel != null ? $student->CurrentGradeLevel : '');

            $grantAmount = floatval($amount);
            if ($payable != null) {
                $paidAmount = $payable->AmountPaid != null ? floatval($payable->AmountPaid) : 0;

                if ($student != null && $class != null) {
                    if (($class->Year == 'Grade 11' | $class->Year == 'Grade 12') && env('SENIOR_HIGH_SEM_ENROLLMENT') === 'BREAK') {
                        $dsc = $payable->DiscountAmount != null ? floatval($payable->DiscountAmount) : 0;

                        $payable->DiscountAmount = ($dsc + ($amount / 2));
                        $payable->AmountPayable = floatval($payable->AmountPayable) - (floatval($amount) / 2);
                        $payable->Balance = floatval($payable->Balance) - (floatval($amount) / 2);
                        $payable->save();

                        $grantAmount = $grantAmount / 2;
                    } else {
                        $dsc = $payable->DiscountAmount != null ? floatval($payable->DiscountAmount) : 0;

                        $payable->DiscountAmount = ($dsc + $amount);
                        $payable->AmountPayable = floatval($payable->AmountPayable) - floatval($amount);
                        $payable->Balance = floatval($payable->Balance) - floatval($amount);
                        $payable->save();
                    }
                } else {
                    $dsc = $payable->DiscountAmount != null ? floatval($payable->DiscountAmount) : 0;

                    $payable->DiscountAmount = ($dsc + $amount);
                    $payable->AmountPayable = floatval($payable->AmountPayable) - floatval($amount);
                    $payable->Balance = floatval($payable->Balance) - floatval($amount);
                    $payable->save();
                }

                // update payable tuitions breakdown
                TuitionsBreakdown::where('PayableId', $payableId)
                    ->delete();

                // recreate tuitions breakdown
                $discount = floatval($payable->DiscountAmount);
                if (($class->Year == 'Grade 11' | $class->Year == 'Grade 12') && env('SENIOR_HIGH_SEM_ENROLLMENT') === 'BREAK') {
                    // if grade 11 and grade 12, only 5 months should be added to the tuitions breakdown
                    $monthsToPay = 5;

                    for ($i=0; $i<$monthsToPay; $i++) {
                        $syStartDate = $sy->MonthStart != null ? $sy->MonthStart : date('Y-m-d');
                        $tuitionBreakdown = new TuitionsBreakdown;
                        $tuitionBreakdown->id = IDGenerator::generateIDandRandString();
                        
                        if ($class->Semester != null && $class->Semester == '2nd') {
                            $tuitionBreakdown->ForMonth = date('Y-m-01', strtotime($syStartDate . ' +' . ($i+5) . ' months'));
                        } else {
                            $tuitionBreakdown->ForMonth = date('Y-m-01', strtotime($syStartDate . ' +' . ($i) . ' months'));
                        }
                        
                        $tuitionBreakdown->PayableId = $payableId;

                        $amntPayable = $payable->AmountPayable > 0 ? ($payable->AmountPayable / $monthsToPay) : 0;
                        $pyblOriginal = $payable->Payable > 0 ? ($payable->Payable / $monthsToPay) : 0;
                        $dscntOriginal = $discount > 0 ? (($discount / 2) / $monthsToPay) : 0;

                        $tuitionBreakdown->AmountPayable = $amntPayable;
                        $tuitionBreakdown->Payable = $pyblOriginal;
                        $tuitionBreakdown->Balance = $amntPayable;
                        $tuitionBreakdown->Discount = $dscntOriginal;
                        $tuitionBreakdown->save();
                    }
                } else {
                    $monthsToPay = 10;

                    for ($i=0; $i<$monthsToPay; $i++) {
                        $syStartDate = $sy->MonthStart != null ? $sy->MonthStart : date('Y-m-d');
                        $tuitionBreakdown = new TuitionsBreakdown;
                        $tuitionBreakdown->id = IDGenerator::generateIDandRandString();
                        $tuitionBreakdown->ForMonth = date('Y-m-01', strtotime($syStartDate . ' +' . ($i) . ' months'));
                        $tuitionBreakdown->PayableId = $payableId;

                        $amntPayable = $payable->AmountPayable > 0 ? ($payable->AmountPayable / $monthsToPay) : 0;
                        $pyblOriginal = $payable->Payable > 0 ? ($payable->Payable / $monthsToPay) : 0;
                        $dscntOriginal = $discount > 0 ? ($discount / $monthsToPay) : 0;

                        $tuitionBreakdown->AmountPayable = $amntPayable;
                        $tuitionBreakdown->Payable = $pyblOriginal;
                        $tuitionBreakdown->Balance = $amntPayable;
                        $tuitionBreakdown->Discount = $dscntOriginal;
                        $tuitionBreakdown->save();
                    }
                }

                // update tutions breakdown payments
                if ($paidAmount > 0) {
                    // update tuitions breakdown
                    $tBreakdown = TuitionsBreakdown::where('PayableId', $payableId)->whereRaw("Balance > 0")->orderBy('ForMonth')->get();
                    $payment = $paidAmount;
                    foreach($tBreakdown as $item) {
                        $currentPayable = floatval($item->Balance);
                        if ($payment > 0) {
                            if ($payment >= $currentPayable) {
                                $item->Balance = 0;
                                $item->AmountPaid = $item->AmountPayable;
                                
                                $payment = $payment - $currentPayable;
                            } else {
                                $item->Balance = $currentPayable - $payment;
                                $item->AmountPaid = floatval($item->AmountPaid) + $payment;

                                $payment = 0;
                            }

                            $item->save();
                        }
                    }
                }
            }
        }

        return response()->json($scholarship, 200);
    }

    public function removeScholarship(Request $request) {
        $id = $request['id'];

        $scholarship = StudentScholarships::find($id);

        if (Auth::user()->hasAnyPermission(['god permission', 'remove scholarship grant'])) {
            if ($scholarship != null) {
                $student = Students::find($scholarship->StudentId);
                $class = Classes::find($student != null && $student->CurrentGradeLevel != null ? $student->CurrentGradeLevel : '');
                
                if ($scholarship->DeductMonthly === 'Yes') {
                    $grantAmount = floatval($scholarship->Amount);

                    // update payable
                    $payable = Payables::find($scholarship->PayableId);

                    if ($payable != null && $student != null && $class != null) {
                        $sy = SchoolYear::find($class->SchoolYearId);
                        $paidAmount = $payable->AmountPaid != null ? floatval($payable->AmountPaid) : 0;

                        if (($class->Year == 'Grade 11' | $class->Year == 'Grade 12') && env('SENIOR_HIGH_SEM_ENROLLMENT') === 'BREAK') {
                            $dsc = $payable->DiscountAmount != null ? floatval($payable->DiscountAmount) : 0;
                            $dscAmount = ($dsc - ($grantAmount / 2));
            
                            $payable->DiscountAmount = $dscAmount < 0 ? 0 : $dscAmount;
                            $payable->Balance = floatval($payable->Balance) + (floatval($grantAmount) / 2);
                            $payable->AmountPayable = floatval($payable->AmountPayable) + $grantAmount;
                            $payable->save();

                            $grantAmount = $grantAmount / 2;
                        } else {
                            $dsc = $payable->DiscountAmount != null ? floatval($payable->DiscountAmount) : 0;
                            $dscAmount = ($dsc - $grantAmount);
            
                            $payable->DiscountAmount = $dscAmount < 0 ? 0 : $dscAmount;
                            $payable->Balance = floatval($payable->Balance) + floatval($grantAmount);
                            $payable->AmountPayable = floatval($payable->AmountPayable) + $grantAmount;
                            $payable->save();
                        }

                        // update payable tuitions breakdown
                        TuitionsBreakdown::where('PayableId', $payable->id)
                        ->delete();

                        // recreate tuitions breakdown
                        $discount = floatval($payable->DiscountAmount);
                        if (($class->Year == 'Grade 11' | $class->Year == 'Grade 12') && env('SENIOR_HIGH_SEM_ENROLLMENT') === 'BREAK') {
                            // if grade 11 and grade 12, only 5 months should be added to the tuitions breakdown
                            $monthsToPay = 5;

                            for ($i=0; $i<$monthsToPay; $i++) {
                                $syStartDate = $sy->MonthStart != null ? $sy->MonthStart : date('Y-m-d');
                                $tuitionBreakdown = new TuitionsBreakdown;
                                $tuitionBreakdown->id = IDGenerator::generateIDandRandString();
                                
                                if ($class->Semester != null && $class->Semester == '2nd') {
                                    $tuitionBreakdown->ForMonth = date('Y-m-01', strtotime($syStartDate . ' +' . ($i+5) . ' months'));
                                } else {
                                    $tuitionBreakdown->ForMonth = date('Y-m-01', strtotime($syStartDate . ' +' . ($i) . ' months'));
                                }
                                
                                $tuitionBreakdown->PayableId = $payable->id;

                                $amntPayable = $payable->AmountPayable > 0 ? ($payable->AmountPayable / $monthsToPay) : 0;
                                $pyblOriginal = $payable->Payable > 0 ? ($payable->Payable / $monthsToPay) : 0;
                                $dscntOriginal = $discount > 0 ? (($discount / 2) / $monthsToPay) : 0;

                                $tuitionBreakdown->AmountPayable = $amntPayable;
                                $tuitionBreakdown->Payable = $pyblOriginal;
                                $tuitionBreakdown->Balance = $amntPayable;
                                $tuitionBreakdown->Discount = $dscntOriginal;
                                $tuitionBreakdown->save();
                            }
                        } else {
                            $monthsToPay = 10;

                            for ($i=0; $i<$monthsToPay; $i++) {
                                $syStartDate = $sy->MonthStart != null ? $sy->MonthStart : date('Y-m-d');
                                $tuitionBreakdown = new TuitionsBreakdown;
                                $tuitionBreakdown->id = IDGenerator::generateIDandRandString();
                                $tuitionBreakdown->ForMonth = date('Y-m-01', strtotime($syStartDate . ' +' . ($i) . ' months'));
                                $tuitionBreakdown->PayableId = $payable->id;

                                $amntPayable = $payable->AmountPayable > 0 ? ($payable->AmountPayable / $monthsToPay) : 0;
                                $dscntOriginal = $discount > 0 ? ($discount / $monthsToPay) : 0;

                                $tuitionBreakdown->AmountPayable = $amntPayable;
                                $tuitionBreakdown->Payable = $amntPayable;
                                $tuitionBreakdown->Balance = $amntPayable;
                                $tuitionBreakdown->Discount = $dscntOriginal;
                                $tuitionBreakdown->save();
                            }
                        }

                        // update tutions breakdown payments
                        if ($paidAmount > 0) {
                            // update tuitions breakdown
                            $tBreakdown = TuitionsBreakdown::where('PayableId', $payable->id)->whereRaw("Balance > 0")->orderBy('ForMonth')->get();
                            $payment = $paidAmount;
                            foreach($tBreakdown as $item) {
                                $currentPayable = floatval($item->Balance);
                                if ($payment > 0) {
                                    if ($payment >= $currentPayable) {
                                        $item->Balance = 0;
                                        $item->AmountPaid = $item->AmountPayable;
                                        
                                        $payment = $payment - $currentPayable;
                                    } else {
                                        $item->Balance = $currentPayable - $payment;
                                        $item->AmountPaid = floatval($item->AmountPaid) + $payment;

                                        $payment = 0;
                                    }

                                    $item->save();
                                }
                            }
                        }
                    }                        
                }

                $scholarship->delete();
            }

            return response()->json($scholarship, 200);
        } else {
            return response()->json('You are not allowed to access this function.', 403);
        }
    }

    
    public function autoApplyScholarshipFromCashier(Request $request) {
        $payableId = $request['PayableId'];
        $studentId = $request['StudentId'];
        $schoolYear = $request['SchoolYear'];
        $type = $request['Type'];
        $amount = $request['Amount'];
        $deductMonthly = 'Yes';
        
        $student = Students::find($studentId);

        if ($type === 'ESC') {
            $scholarshipSource = Scholarships::find(env('ESC_SCHOLARSHIP_ID'));
        } else {
            if ($student->FromSchool === 'Public') {
                $scholarshipSource = Scholarships::find(env('VMS_PUBLIC_SCHOLARSHIP_ID'));
            } else {
                $scholarshipSource = Scholarships::find(env('VMS_PUBLIC_SCHOLARSHIP_ID'));
            }
        }

        if ($scholarshipSource != null) {
            // save scholarship profile
            $id = IDGenerator::generateIDandRandString();
            $scholarship = new StudentScholarships;
            $scholarship->id = $id;
            $scholarship->PayableId = $payableId;
            $scholarship->SchoolYear = $schoolYear;
            $scholarship->ScholarshipId = $scholarshipSource->id;
            $scholarship->Amount = $scholarshipSource->Amount;
            $scholarship->StudentId = $studentId;
            $scholarship->DeductMonthly = $deductMonthly;
            $scholarship->save();

            $sy = SchoolYear::where('SchoolYear', $schoolYear)->first();

            // update student if esc scholar
            $scholar = Scholarships::find($scholarshipId);
            if ($scholar != null) {
                Students::where('id', $studentId)
                        ->update(['ESCScholar' => 'Yes']);
            }

            // update payable
            if ($deductMonthly === 'Yes') {
                $payable = Payables::find($payableId);

                $class = Classes::find($student != null && $student->CurrentGradeLevel != null ? $student->CurrentGradeLevel : '');

                $grantAmount = floatval($amount);
                if ($payable != null) {
                    $paidAmount = $payable->AmountPaid != null ? floatval($payable->AmountPaid) : 0;

                    if ($student != null && $class != null) {
                        if (($class->Year == 'Grade 11' | $class->Year == 'Grade 12') && env('SENIOR_HIGH_SEM_ENROLLMENT') === 'BREAK') {
                            $dsc = $payable->DiscountAmount != null ? floatval($payable->DiscountAmount) : 0;

                            $payable->DiscountAmount = ($dsc + ($amount / 2));
                            $payable->AmountPayable = floatval($payable->AmountPayable) - (floatval($amount) / 2);
                            $payable->Balance = floatval($payable->Balance) - (floatval($amount) / 2);
                            $payable->save();

                            $grantAmount = $grantAmount / 2;
                        } else {
                            $dsc = $payable->DiscountAmount != null ? floatval($payable->DiscountAmount) : 0;

                            $payable->DiscountAmount = ($dsc + $amount);
                            $payable->AmountPayable = floatval($payable->AmountPayable) - floatval($amount);
                            $payable->Balance = floatval($payable->Balance) - floatval($amount);
                            $payable->save();
                        }
                    } else {
                        $dsc = $payable->DiscountAmount != null ? floatval($payable->DiscountAmount) : 0;

                        $payable->DiscountAmount = ($dsc + $amount);
                        $payable->AmountPayable = floatval($payable->AmountPayable) - floatval($amount);
                        $payable->Balance = floatval($payable->Balance) - floatval($amount);
                        $payable->save();
                    }

                    // update payable tuitions breakdown
                    TuitionsBreakdown::where('PayableId', $payableId)
                        ->delete();

                    // recreate tuitions breakdown
                    $discount = floatval($payable->DiscountAmount);
                    if (($class->Year == 'Grade 11' | $class->Year == 'Grade 12') && env('SENIOR_HIGH_SEM_ENROLLMENT') === 'BREAK') {
                        // if grade 11 and grade 12, only 5 months should be added to the tuitions breakdown
                        $monthsToPay = 5;

                        for ($i=0; $i<$monthsToPay; $i++) {
                            $syStartDate = $sy->MonthStart != null ? $sy->MonthStart : date('Y-m-d');
                            $tuitionBreakdown = new TuitionsBreakdown;
                            $tuitionBreakdown->id = IDGenerator::generateIDandRandString();
                            
                            if ($class->Semester != null && $class->Semester == '2nd') {
                                $tuitionBreakdown->ForMonth = date('Y-m-01', strtotime($syStartDate . ' +' . ($i+5) . ' months'));
                            } else {
                                $tuitionBreakdown->ForMonth = date('Y-m-01', strtotime($syStartDate . ' +' . ($i) . ' months'));
                            }
                            
                            $tuitionBreakdown->PayableId = $payableId;

                            $amntPayable = $payable->AmountPayable > 0 ? ($payable->AmountPayable / $monthsToPay) : 0;
                            $pyblOriginal = $payable->Payable > 0 ? ($payable->Payable / $monthsToPay) : 0;
                            $dscntOriginal = $discount > 0 ? (($discount / 2) / $monthsToPay) : 0;

                            $tuitionBreakdown->AmountPayable = $amntPayable;
                            $tuitionBreakdown->Payable = $pyblOriginal;
                            $tuitionBreakdown->Balance = $amntPayable;
                            $tuitionBreakdown->Discount = $dscntOriginal;
                            $tuitionBreakdown->save();
                        }
                    } else {
                        $monthsToPay = 10;

                        for ($i=0; $i<$monthsToPay; $i++) {
                            $syStartDate = $sy->MonthStart != null ? $sy->MonthStart : date('Y-m-d');
                            $tuitionBreakdown = new TuitionsBreakdown;
                            $tuitionBreakdown->id = IDGenerator::generateIDandRandString();
                            $tuitionBreakdown->ForMonth = date('Y-m-01', strtotime($syStartDate . ' +' . ($i) . ' months'));
                            $tuitionBreakdown->PayableId = $payableId;

                            $amntPayable = $payable->AmountPayable > 0 ? ($payable->AmountPayable / $monthsToPay) : 0;
                            $pyblOriginal = $payable->Payable > 0 ? ($payable->Payable / $monthsToPay) : 0;
                            $dscntOriginal = $discount > 0 ? ($discount / $monthsToPay) : 0;

                            $tuitionBreakdown->AmountPayable = $amntPayable;
                            $tuitionBreakdown->Payable = $pyblOriginal;
                            $tuitionBreakdown->Balance = $amntPayable;
                            $tuitionBreakdown->Discount = $dscntOriginal;
                            $tuitionBreakdown->save();
                        }
                    }

                    // update tutions breakdown payments
                    if ($paidAmount > 0) {
                        // update tuitions breakdown
                        $tBreakdown = TuitionsBreakdown::where('PayableId', $payableId)->whereRaw("Balance > 0")->orderBy('ForMonth')->get();
                        $payment = $paidAmount;
                        foreach($tBreakdown as $item) {
                            $currentPayable = floatval($item->Balance);
                            if ($payment > 0) {
                                if ($payment >= $currentPayable) {
                                    $item->Balance = 0;
                                    $item->AmountPaid = $item->AmountPayable;
                                    
                                    $payment = $payment - $currentPayable;
                                } else {
                                    $item->Balance = $currentPayable - $payment;
                                    $item->AmountPaid = floatval($item->AmountPaid) + $payment;

                                    $payment = 0;
                                }

                                $item->save();
                            }
                        }
                    }
                }
            }
        }

        return response()->json('ok', 200);
    }
}
