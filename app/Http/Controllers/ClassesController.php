<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClassesRequest;
use App\Http\Requests\UpdateClassesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ClassesRepository;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\ClassesRepo;
use App\Models\Students;
use App\Models\StudentClasses;
use App\Models\SchoolYear;
use App\Models\IDGenerator;
use App\Models\Payables;
use App\Models\StudentSubjects;
use App\Models\TuitionsBreakdown;
use App\Models\PayableInclusions;
use App\Models\TuitionInclusions;
use App\Models\Transactions;
use App\Models\StudentScholarships;
use App\Models\SubjectClasses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;

class ClassesController extends AppBaseController
{
    /** @var ClassesRepository $classesRepository*/
    private $classesRepository;

    public function __construct(ClassesRepository $classesRepo)
    {
        $this->middleware('auth');
        $this->classesRepository = $classesRepo;
    }

    /**
     * Display a listing of the Classes.
     */
    public function index(Request $request)
    {
        $classes = $this->classesRepository->paginate(10);

        return view('classes.index')
            ->with('classes', $classes);
    }

    /**
     * Show the form for creating a new Classes.
     */
    public function create()
    {
        return view('classes.create');
    }

    /**
     * Store a newly created Classes in storage.
     */
    public function store(CreateClassesRequest $request)
    {
        $input = $request->all();

        $classes = $this->classesRepository->create($input);

        Flash::success('Classes saved successfully.');

        return redirect(route('classes.index'));
    }

    /**
     * Display the specified Classes.
     */
    public function show($id)
    {
        $classes = DB::table('Classes')
            ->leftJoin('SchoolYear', 'Classes.SchoolYearId', '=', 'SchoolYear.id')
            ->whereRaw("Classes.id='" . $id . "'")
            ->select('Classes.*', 'SchoolYear.SchoolYear')
            ->first();

        $subjects = DB::table('StudentSubjects')
            ->leftJoin('Subjects', 'StudentSubjects.SubjectId', '=', 'Subjects.id')
            ->whereRaw("ClassId='" . $id . "'")
            ->select(
                'StudentSubjects.SubjectId', 
                'Subjects.Subject'
            )
            ->groupBy('StudentSubjects.SubjectId', 'Subjects.Subject')
            ->orderBy('Subjects.Subject')
            ->get();

        if (empty($classes)) {
            Flash::error('Classes not found');

            return redirect(route('classes.index'));
        }

        return view('classes.show', [
            'class' => $classes,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Show the form for editing the specified Classes.
     */
    public function edit($id)
    {
        $classes = $this->classesRepository->find($id);

        if (empty($classes)) {
            Flash::error('Classes not found');

            return redirect(route('classes.index'));
        }

        return view('classes.edit')->with('classes', $classes);
    }

    /**
     * Update the specified Classes in storage.
     */
    public function update($id, UpdateClassesRequest $request)
    {
        $classes = $this->classesRepository->find($id);

        if (empty($classes)) {
            Flash::error('Classes not found');

            return redirect(route('classes.index'));
        }

        $classes = $this->classesRepository->update($request->all(), $id);

        Flash::success('Classes updated successfully.');

        return redirect(route('classes.index'));
    }

    /**
     * Remove the specified Classes from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $classes = $this->classesRepository->find($id);

        if (empty($classes)) {
            Flash::error('Classes not found');

            return redirect(route('classes.index'));
        }

        $this->classesRepository->delete($id);

        Flash::success('Classes deleted successfully.');

        return redirect(route('classes.index'));
    }

    public function enroll($studentId) {
        if (Auth::user()->hasAnyPermission(['god permission', 'enroll student class'])) {
            return view('/classes/enroll', [
                'studentId' => $studentId
            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    public function existingStudent(Request $request) {
        if (Auth::user()->hasAnyPermission(['god permission', 'enroll student class'])) {
            return view('/classes/existing_student', [

            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * WITH SEM TUITION COMPUTATION
     */
    public function saveEnrollment(Request $request) {
        $studentId = $request['StudentId'];
        $classesRepoId = $request['ClassRepoId'];
        $syId = $request['SchoolYearId'];
        $subjects = $request['Subjects'];
        $type = $request['Type'];
        $semester = $request['Semester'];

        $sy = SchoolYear::find($syId);
        $classesRepo = ClassesRepo::find($classesRepoId);
        $student = Students::find($studentId);

        if ($student != null) {
            if ($classesRepo != null) {
                // check if class exists in a particular school year
                $class = Classes::where('SchoolYearId', $syId)
                    ->where('Year', $classesRepo->Year)
                    ->where('Section', $classesRepo->Section)
                    ->where('Strand', $classesRepo->Strand)
                    ->where('Semester', $semester)
                    ->first();

                // save class if not yet created
                if ($class == null) {
                    $classId = IDGenerator::generateID();
                    $class = new Classes;
                    $class->id = $classId;
                    $class->SchoolYearId = $syId;
                    $class->Year = $classesRepo->Year;
                    $class->Section = $classesRepo->Section;
                    $class->Adviser = $classesRepo->Adviser;
                    $class->Strand = $classesRepo->Strand;
                    $class->Semester = $semester;
                    $class->save();
                } else { 
                    $classId = $class->id;
                }

                // create student inside the class
                // check student first if enrolled already in class
                $enrollee = StudentClasses::where('ClassId', $classId)
                    ->where('StudentId', $studentId)
                    ->first();
                
                if ($enrollee != null) {
                    return response()->json('Student already enrolled in this class!', 403);
                } else {
                    // create enrollee/student
                    $enrollee = new StudentClasses;
                    $enrollee->id = IDGenerator::generateID();
                    $enrollee->ClassId = $classId;
                    $enrollee->StudentId = $studentId;
                    $enrollee->Status = 'Pending Enrollment Payment';
                    $enrollee->Type = $type;
                    $enrollee->Semester = $semester;
                    $enrollee->save();

                    if (env("TUITION_PROPAGATION_PRESET") === 'STATIC_ENROLLMENT_FEE') {
                        // create payables
                        $payableId = IDGenerator::generateIDandRandString();
                        $payable = new Payables;
                        $payable->id = $payableId;
                        $payable->StudentId = $studentId;
                        $payable->AmountPayable = 700.00;
                        $payable->PaymentFor = 'Enrollment Fees for ' . $sy->SchoolYear;
                        $payable->Category = 'Enrollment';
                        $payable->Balance = 700.00;
                        $payable->SchoolYear = $sy->SchoolYear;
                        $payable->save();
                    }

                    // create subjects
                    foreach($subjects as $item) {
                        if ($item['Selected'] | $item['Selected']==='true') {
                            $studentSubjects = new StudentSubjects;
                            $studentSubjects->id = IDGenerator::generateIDandRandString();
                            $studentSubjects->StudentId = $studentId;
                            $studentSubjects->SubjectId = $item['id'];
                            $studentSubjects->ClassId = $classId;
                            $studentSubjects->TeacherId = $item['TeacherId'];
                            $studentSubjects->save();
                        }
                    }
                    
                    // update student current grade level
                    $student->CurrentGradeLevel = $classId;
                    $student->save();

                    /*
                     * ======================================================
                     * ADD TUITION FEE PAYABLES
                     * ======================================================
                     */
                    $class = Classes::find($classId);
                    if ($class != null) {
                        $classRepo = ClassesRepo::where('Year', $class->Year)
                            ->where('Section', $class->Section)
                            ->where('Strand', $class->Strand)
                            ->where('Semester', $class->Semester)
                            ->first();
                        
                        $sy = SchoolYear::find($class->SchoolYearId);

                        if ($classRepo != null) {
                            $baseTuition = $student->FromSchool === 'Private' ? $classRepo->BaseTuitionFee : ($classRepo->BaseTuitionFeePublic != null ? $classRepo->BaseTuitionFeePublic : $classRepo->BaseTuitionFee); // private is the default

                            $payableId = IDGenerator::generateIDandRandString();
                            $tuitionPayable = new Payables;
                            $tuitionPayable->id = $payableId;
                            $tuitionPayable->StudentId = $studentId;
                            $tuitionPayable->PaymentFor = 'Tuition Fee for ' . ($sy != null ? $sy->SchoolYear : '(no school year declared)');
                            $tuitionPayable->Category = 'Tuition Fees';
                            $tuitionPayable->SchoolYear = $sy->SchoolYear;
                            $tuitionPayable->ClassId = $classId;

                            if ($baseTuition != null) {
                                // copy base tuition fee if declared in classes
                                $tuitionPayable->Payable = $baseTuition;
                                $tuitionPayable->AmountPayable = $baseTuition;
                                $tuitionPayable->Balance = $baseTuition;
                            } else {
                                // get tuition per subject if not declared in classes
                                $totalSubjectTuition = DB::table('SubjectClasses')
                                    ->leftJoin('Subjects', 'SubjectClasses.SubjectId', '=', 'Subjects.id')
                                    ->whereRaw("SubjectClasses.ClassRepoId='" . $classRepo->id . "'")
                                    ->select(
                                        DB::raw("SUM(Subjects.CourseFee) AS Total")
                                    )
                                    ->first();

                                if ($totalSubjectTuition != null) {
                                    $tuitionPayable->Payable = $totalSubjectTuition->Total;
                                    $tuitionPayable->AmountPayable = $totalSubjectTuition->Total;
                                    $tuitionPayable->Balance = $totalSubjectTuition->Total;
                                } else {
                                    $tuitionPayable->Payable = 0.0;
                                    $tuitionPayable->AmountPayable = 0.0;
                                    $tuitionPayable->Balance = 0.0;
                                }
                            }

                            // create payable tuition inclusion
                            $tuitionInclusions = TuitionInclusions::where('ClassRepoId', $classRepo->id)
                                ->where('FromSchool', $student->FromSchool != null ? $student->FromSchool : 'Private')
                                ->get();
                            if ($tuitionInclusions != null) {
                                foreach($tuitionInclusions as $item) {
                                    $payableInclusions = new PayableInclusions;
                                    $payableInclusions->id = IDGenerator::generateIDandRandString();
                                    $payableInclusions->PayableId = $payableId;
                                    $payableInclusions->ItemName = $item->ItemName;
                                    $payableInclusions->Amount = $item->Amount;
                                    $payableInclusions->save();
                                }
                            }

                            // create tuitions breakdown
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
    
                                    $amntPayable = $tuitionPayable->AmountPayable > 0 ? ($tuitionPayable->AmountPayable / $monthsToPay) : 0;
    
                                    $tuitionBreakdown->AmountPayable = $amntPayable;
                                    $tuitionBreakdown->Payable = $amntPayable;
                                    $tuitionBreakdown->Balance = $amntPayable;
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
    
                                    $amntPayable = $tuitionPayable->AmountPayable > 0 ? ($tuitionPayable->AmountPayable / $monthsToPay) : 0;
    
                                    $tuitionBreakdown->AmountPayable = $amntPayable;
                                    $tuitionBreakdown->Payable = $amntPayable;
                                    $tuitionBreakdown->Balance = $amntPayable;
                                    $tuitionBreakdown->save();
                                }
                            }

                            $tuitionPayable->save();
                        }
                    }
                }
            } else {
                return response()->json('Class repository not found!', 404);
            }
        } else {
            return response()->json('Student not found!', 404);
        }
    }

    public function getStudentsFromClass(Request $request) {
        $classId = $request['ClassId'];

        $students = DB::table('StudentClasses')
            ->leftJoin('Students', 'StudentClasses.StudentId', '=', 'Students.id')
            ->leftJoin('Towns', 'Students.Town', '=', 'Towns.id')
            ->leftJoin('Barangays', 'Students.Barangay', '=', 'Barangays.id')
            ->whereRaw("StudentClasses.ClassId='" . $classId . "'")
            ->select(
                'Students.*',
                'StudentClasses.Status',
                'StudentClasses.Type',
                'StudentClasses.created_at AS EnrollmentDate',
                'StudentClasses.EnrollmentORDate',
                'Towns.Town AS TownSpelled',
                'Barangays.Barangay AS BarangaySpelled',
            )
            ->orderBy('Students.LastName')
            ->get();

        return response()->json($students, 200);
    }

    public function getTuitionBreakdown(Request $request) {
        $payableId = $request['PayableId'];

        $data = DB::table('TuitionsBreakdown')
            ->whereRaw("PayableId='" . $payableId . "' AND Balance > 0")
            ->orderBy('ForMonth')
            ->get();

        return response()->json($data, 200);
    }

    public function viewClass($adviserId, $syId, $classId) {
        if (Auth::user()->hasAnyPermission(['god permission', 'view class'])) {
            return view('classes.show', [
                'adviser' => $adviserId,
                'schoolYearId' => $syId,
                'classId' => $classId,
            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
        
    }

    public function transferToAnotherClass($studentId) {
        if (Auth::user()->hasAnyPermission(['god permission', 'transfer student class'])) {
            return view('/classes/transfer_to_another_class', [
                'studentId' => $studentId,
            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * WITH SEM TUITION COMPUTATION
     */
    public function saveTransfer(Request $request) {
        $studentId = $request['StudentId'];
        $currentClassId = $request['CurrentClassId'];
        $syId = $request['SchoolYearId'];
        $transferedClassId = $request['TransferedClassId'];
        $subjects = $request['Subjects'];
        $reason = $request['Reason'];
        $semester = $request['Semester'];

        $student = Students::find($studentId);
        $sy = SchoolYear::find($syId);
        $classesRepo = ClassesRepo::find($transferedClassId);

        if ($student != null) {
            if ($classesRepo != null) {
                // check if class exists in a particular school year
                $class = Classes::where('SchoolYearId', $syId)
                    ->where('Year', $classesRepo->Year)
                    ->where('Section', $classesRepo->Section)
                    ->where('Strand', $classesRepo->Strand)
                    ->where('Semester', $semester)
                    ->first();

                // save class if not yet created
                if ($class == null) {
                    $classId = IDGenerator::generateID();
                    $class = new Classes;
                    $class->id = $classId;
                    $class->SchoolYearId = $syId;
                    $class->Year = $classesRepo->Year;
                    $class->Section = $classesRepo->Section;
                    $class->Adviser = $classesRepo->Adviser;
                    $class->Strand = $classesRepo->Strand;
                    $class->Semester = $semester;
                    $class->save();
                } else { 
                    $classId = $class->id;
                }

                 // create student inside the class
                // check student first if enrolled already in class
                $enrollee = StudentClasses::where('ClassId', $classId)
                    ->where('StudentId', $studentId)
                    ->first();
                
                if ($enrollee != null) {
                    return response()->json('Student is already enrolled in this class!', 403);
                } else {
                    // delete previous enrollment
                    $prevClass = StudentClasses::where('ClassId', $currentClassId)
                        ->where('StudentId', $studentId)
                        ->first();

                    if ($prevClass != null) {
                        $prevClass->delete();
                    }
                    
                    // create enrollee/student
                    $enrollee = new StudentClasses;
                    $enrollee->id = IDGenerator::generateID();
                    $enrollee->ClassId = $classId;
                    $enrollee->StudentId = $studentId;
                    $enrollee->Status = 'Paid';
                    $enrollee->Type = 'Transfered';
                    $enrollee->Semester = $semester;
                    $enrollee->EnrollmentORNumber = $prevClass != null ? $prevClass->EnrollmentORNumber : '';
                    $enrollee->EnrollmentORDate = $prevClass != null ? $prevClass->EnrollmentORDate : '';
                    $enrollee->EnrollmentStatus = 'Transfered Enrollment';
                    $enrollee->Notes = $reason . ' (From Class ID ' . $currentClassId . ')';
                    $enrollee->PreviousClassId = $currentClassId;
                    $enrollee->save();

                    // create subjects
                    foreach($subjects as $item) {
                        if ($item['Selected'] | $item['Selected']==='true') {
                            $studentSubjects = StudentSubjects::where('StudentId', $studentId)
                                ->where('ClassId', $classId)
                                ->where('SubjectId', $item['id'])
                                ->where('TeacherId', $item['TeacherId'])
                                ->first();

                            if ($studentSubjects == null) {
                                $studentSubjects = new StudentSubjects;
                                $studentSubjects->id = IDGenerator::generateIDandRandString();
                                $studentSubjects->StudentId = $studentId;
                                $studentSubjects->SubjectId = $item['id'];
                                $studentSubjects->ClassId = $classId;
                                $studentSubjects->TeacherId = $item['TeacherId'];
                                $studentSubjects->save();
                            }
                        }
                    }
                    
                    // update student current grade level
                    $student->CurrentGradeLevel = $classId;
                    $student->save();

                    /**
                     * =====================================================
                     * CONFIGURE TUITION FEE HERE
                     * =====================================================
                     */
                    // delete first existing tuition feest
                    $tpExisting = Payables::where('StudentId', $studentId)
                        ->where('ClassId', $currentClassId)
                        ->first();
                    $amountPaid = 0;
                    if ($tpExisting != null) {
                        // delete tuitions breakdown
                        TuitionsBreakdown::where('PayableId', $tpExisting->id)
                            ->delete();

                        // delete payable inclusions
                        PayableInclusions::where('PayableId', $tpExisting->id)
                        ->delete();

                        $amountPaid = $tpExisting->AmountPaid != null && is_numeric($tpExisting->AmountPaid) ? floatval($tpExisting->AmountPaid) : 0;
                        
                        $payableId = $tpExisting->id;

                        // update scholarship id
                        $scholarship = StudentScholarships::where('PayableId', $tpExisting->id)
                                ->where('StudentId', $studentId)
                                ->where("DeductMonthly", "Yes")
                                ->update(['id' => $payableId]);          

                        $tpExisting->delete();
                    } else {
                        $payableId = IDGenerator::generateIDandRandString();
                    }


                    /*
                     * ======================================================
                     * ADD TUITION FEE PAYABLES
                     * ======================================================
                     */
                    $class = Classes::find($classId);
                    if ($class != null) {
                        $classRepo = ClassesRepo::where('Year', $class->Year)
                            ->where('Section', $class->Section)
                            ->where('Strand', $class->Strand)
                            ->where('Semester', $class->Semester)
                            ->first();
                        
                        $sy = SchoolYear::find($class->SchoolYearId);

                        if ($classRepo != null) {
                            $baseTuition = $student->FromSchool === 'Private' ? $classRepo->BaseTuitionFee : ($classRepo->BaseTuitionFeePublic != null ? $classRepo->BaseTuitionFeePublic : $classRepo->BaseTuitionFee); // private is the default

                            $tuitionPayable = new Payables;
                            $tuitionPayable->id = $payableId;
                            $tuitionPayable->StudentId = $studentId;
                            $tuitionPayable->PaymentFor = 'Tuition Fee for ' . ($sy != null ? $sy->SchoolYear : '(no school year declared)');
                            $tuitionPayable->Category = 'Tuition Fees';
                            $tuitionPayable->SchoolYear = $sy->SchoolYear;
                            $tuitionPayable->ClassId = $classId;

                            if ($baseTuition != null) {
                                // copy base tuition fee if declared in classes
                                $tuitionPayable->Payable = $baseTuition;
                                $tuitionPayable->AmountPayable = $baseTuition;
                                $tuitionPayable->Balance = $baseTuition;
                            } else {
                                // get tuition per subject if not declared in classes
                                $totalSubjectTuition = DB::table('SubjectClasses')
                                    ->leftJoin('Subjects', 'SubjectClasses.SubjectId', '=', 'Subjects.id')
                                    ->whereRaw("SubjectClasses.ClassRepoId='" . $classRepo->id . "'")
                                    ->select(
                                        DB::raw("SUM(Subjects.CourseFee) AS Total")
                                    )
                                    ->first();

                                if ($totalSubjectTuition != null) {
                                    $tuitionPayable->Payable = $totalSubjectTuition->Total;
                                    $tuitionPayable->AmountPayable = $totalSubjectTuition->Total;
                                    $tuitionPayable->Balance = $totalSubjectTuition->Total;
                                } else {
                                    $tuitionPayable->Payable = 0.0;
                                    $tuitionPayable->AmountPayable = 0.0;
                                    $tuitionPayable->Balance = 0.0;
                                }
                            }

                            // create payable tuition inclusion
                            $tuitionInclusions = TuitionInclusions::where('ClassRepoId', $classRepo->id)
                                ->where('FromSchool', $student->FromSchool != null ? $student->FromSchool : 'Private')
                                ->get();
                            if ($tuitionInclusions != null) {
                                foreach($tuitionInclusions as $item) {
                                    $payableInclusions = new PayableInclusions;
                                    $payableInclusions->id = IDGenerator::generateIDandRandString();
                                    $payableInclusions->PayableId = $payableId;
                                    $payableInclusions->ItemName = $item->ItemName;
                                    $payableInclusions->Amount = $item->Amount;
                                    $payableInclusions->save();
                                }
                            }

                            // create tuitions breakdown
                            if (($class->Year == 'Grade 11' | $class->Year == 'Grade 12') && env('SENIOR_HIGH_SEM_ENROLLMENT') === 'BREAK') {
                                // update payable, set to half per sem
                                $tuitionPayable->Payable = $tuitionPayable->Payable > 0 ? ($tuitionPayable->Payable / 2) : 0;
                                $tuitionPayable->AmountPayable = $tuitionPayable->AmountPayable > 0 ? ($tuitionPayable->AmountPayable / 2) : 0;
                                $tuitionPayable->Balance = $tuitionPayable->Balance > 0 ? ($tuitionPayable->Balance / 2) : 0;

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
    
                                    $amntPayable = $tuitionPayable->AmountPayable > 0 ? ($tuitionPayable->AmountPayable / $monthsToPay) : 0;
    
                                    $tuitionBreakdown->AmountPayable = $amntPayable;
                                    $tuitionBreakdown->Payable = $amntPayable;
                                    $tuitionBreakdown->Balance = $amntPayable;
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
    
                                    $amntPayable = $tuitionPayable->AmountPayable > 0 ? ($tuitionPayable->AmountPayable / $monthsToPay) : 0;
    
                                    $tuitionBreakdown->AmountPayable = $amntPayable;
                                    $tuitionBreakdown->Payable = $amntPayable;
                                    $tuitionBreakdown->Balance = $amntPayable;
                                    $tuitionBreakdown->save();
                                }
                            }

                            $tuitionPayable->save();

                            /**
                             * ==========================================================================
                             * VALIDATE SCHOLARSHIPS
                             * ==========================================================================
                             */
                            $scholarship = StudentScholarships::where('PayableId', $payableId)
                                ->where('StudentId', $studentId)
                                ->where("DeductMonthly", "Yes")
                                ->get();
                        
                            $scholarshipAmount = 0;
                            foreach($scholarship as $item) {
                                $item->PayableId = $payableId;
                                $item->Notes = 'Transfered from Transfer Wizzard';
                                $item->save();

                                $scholarshipAmount += ($item->Amount != null ? floatval($item->Amount) : 0);
                            }

                            if (($class->Year == 'Grade 11' | $class->Year == 'Grade 12') && env('SENIOR_HIGH_SEM_ENROLLMENT') === 'BREAK') {
                                $scholarshipAmount = $scholarshipAmount / 2;
                            }
                            
                            $tuitionPayable = Payables::find($payableId);

                            if ($scholarshipAmount > 0) {
                                $tuitionPayable->DiscountAmount = $scholarshipAmount;
                                $tuitionPayable->AmountPayable = floatval($tuitionPayable->AmountPayable) - $scholarshipAmount;
                                $tuitionPayable->Balance = $tuitionPayable->AmountPayable;
                                $tuitionPayable->save();

                                // update payable tuitions breakdown
                                $tuitionsBreakdown = TuitionsBreakdown::where('PayableId', $payableId)->whereRaw("AmountPaid IS NULL OR AmountPaid = 0")->get();
                                if ($tuitionsBreakdown != null) {
                                    $count = count($tuitionsBreakdown);

                                    if ($count > 0) {
                                        $amountDistributable = round((floatval($scholarshipAmount) / $count), 2);
                                    
                                        foreach($tuitionsBreakdown as $item) {
                                            $item->Discount = $amountDistributable;
                                            $item->AmountPayable = floatval($item->AmountPayable) - floatval($amountDistributable);
                                            $item->Balance = floatval($item->Balance) - floatval($amountDistributable);
                                            $item->save();
                                        }
                                    }
                                }
                            }

                            /**
                             * ==========================================================================
                             * CREDIT THE PREVIOUS PAYMENTS FROM THE PREVIOUS TUITION PAYABLE
                             * ==========================================================================
                             */
                            // update tuitions breakdown
                            if ($amountPaid > 0) {
                                $tBreakdown = TuitionsBreakdown::where('PayableId', $payableId)->whereRaw("Balance > 0")->orderBy('ForMonth')->get();

                                // update transactions
                                Transactions::where('PayablesId', $tpExisting->id)
                                    ->update(['PayablesId' => $payableId]);

                                $payment = $amountPaid;
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
                                        $item->Notes = 'Transfered payments from Transfer Wizzard';
                                        $item->save();
                                    }
                                }

                                // update payable
                                if ($tuitionPayable != null) {
                                    $bal = $tuitionPayable != null && $tuitionPayable->Balance != null && is_numeric($tuitionPayable->Balance) ? floatval($tuitionPayable->Balance) : 0;

                                    $tuitionPayable->AmountPaid = $amountPaid;
                                    $tuitionPayable->Balance = $bal - $amountPaid;
                                    $tuitionPayable->save();
                                }
                            }
                        }
                    }
                }
            }
        }

        return response()->json($student, 200);
    }

    /**
     * WITH SEM TUITION COMPUTATION
     */
    public function batchTransfer(Request $request) {
        $students = $request['Students'];
        $currentClassId = $request['CurrentClassId'];
        $syId = $request['SchoolYearId'];
        $transferedClassId = $request['TransferedClassId'];

        $sy = SchoolYear::find($syId);
        $classesRepo = ClassesRepo::find($transferedClassId);

        if ($classesRepo != null) {
            // check if class exists in a particular school year
            $class = Classes::where('SchoolYearId', $syId)
                ->where('Year', $classesRepo->Year)
                ->where('Section', $classesRepo->Section)
                ->where('Strand', $classesRepo->Strand)
                ->where('Semester', $classesRepo->Semester)
                ->first();

            // save class if not yet created
            if ($class == null) {
                $classId = IDGenerator::generateID();
                $class = new Classes;
                $class->id = $classId;
                $class->SchoolYearId = $syId;
                $class->Year = $classesRepo->Year;
                $class->Section = $classesRepo->Section;
                $class->Adviser = $classesRepo->Adviser;
                $class->Strand = $classesRepo->Strand;
                $class->Semester = $classesRepo->Semester;
                $class->save();
            } else { 
                $classId = $class->id;
            }

            // loop students
            foreach($students as $item) {
                $student = Students::find($item['id']);

                if ($student != null) {
                    // create student inside the class
                    // check student first if enrolled already in class
                    $enrollee = StudentClasses::where('ClassId', $classId)
                        ->where('StudentId', $student->id)
                        ->first();
                    
                    if ($enrollee != null) {
                        // skip if student is already enrolled in the same class
                    } else {
                        // delete previous enrollment
                        $prevClass = StudentClasses::where('ClassId', $currentClassId)
                            ->where('StudentId', $student->id)
                            ->first();

                        if ($prevClass != null) {
                            $prevClass->delete();
                        }
                        
                        // create enrollee/student
                        $enrollee = new StudentClasses;
                        $enrollee->id = IDGenerator::generateID();
                        $enrollee->ClassId = $classId;
                        $enrollee->StudentId = $student->id;
                        $enrollee->Status = 'Paid';
                        $enrollee->Type = 'Transfered';
                        $enrollee->Semester = $classesRepo->Semester;
                        $enrollee->EnrollmentORNumber = $prevClass != null ? $prevClass->EnrollmentORNumber : '';
                        $enrollee->EnrollmentORDate = $prevClass != null ? $prevClass->EnrollmentORDate : '';
                        $enrollee->EnrollmentStatus = 'Transfered Enrollment';
                        $enrollee->Notes = 'Batch Transferred (From Class ID ' . $currentClassId . ')';
                        $enrollee->PreviousClassId = $currentClassId;
                        $enrollee->save();

                        // create subjects
                        $subjects = DB::table('SubjectClasses')
                            ->leftJoin('Subjects', 'SubjectClasses.SubjectId', '=', 'Subjects.id')
                            ->where('ClassRepoId', $classesRepo->id)
                            ->select('SubjectClasses.*', 'Subjects.Teacher')
                            ->get();
                        foreach($subjects as $item) {
                            $studentSubjects = StudentSubjects::where('StudentId', $student->id)
                                ->where('ClassId', $classId)
                                ->where('SubjectId', $item->id)
                                ->where('TeacherId', $item->Teacher)
                                ->first();

                            if ($studentSubjects == null) {
                                $studentSubjects = new StudentSubjects;
                                $studentSubjects->id = IDGenerator::generateIDandRandString();
                                $studentSubjects->StudentId = $student->id;
                                $studentSubjects->SubjectId = $item->id;
                                $studentSubjects->ClassId = $classId;
                                $studentSubjects->TeacherId = $item->Teacher;
                                $studentSubjects->save();
                            }
                        }
                        
                        // update student current grade level
                        $student->CurrentGradeLevel = $classId;
                        $student->save();

                        /**
                         * =====================================================
                         * CONFIGURE TUITION FEE HERE
                         * =====================================================
                         */
                        // delete first existing tuition feest
                        $tpExisting = Payables::where('StudentId', $student->id)
                            ->where('ClassId', $currentClassId)
                            ->first();
                        $amountPaid = 0;
                        $payableId = '';
                        if ($tpExisting != null) {
                            // delete tuitions breakdown
                            TuitionsBreakdown::where('PayableId', $tpExisting->id)
                                ->delete();

                            // delete payable inclusions
                            PayableInclusions::where('PayableId', $tpExisting->id)
                            ->delete();

                            $tpExisting->delete();

                            $amountPaid = $tpExisting->AmountPaid != null && is_numeric($tpExisting->AmountPaid) ? floatval($tpExisting->AmountPaid) : 0;

                            $payableId = $tpExisting->id;
                        } else {
                            $payableId = IDGenerator::generateIDandRandString();
                        }

                        /*
                        * ======================================================
                        * ADD TUITION FEE PAYABLES
                        * ======================================================
                        */
                        $class = Classes::find($classId);
                        if ($class != null) {
                            $classRepo = ClassesRepo::where('Year', $class->Year)
                                ->where('Section', $class->Section)
                                ->where('Strand', $class->Strand)
                                ->where('Semester', $class->Semester)
                                ->first();
                            
                            $sy = SchoolYear::find($class->SchoolYearId);

                            if ($classRepo != null) {
                                $baseTuition = $student->FromSchool === 'Private' ? $classRepo->BaseTuitionFee : ($classRepo->BaseTuitionFeePublic != null ? $classRepo->BaseTuitionFeePublic : $classRepo->BaseTuitionFee); // private is the default

                                $tuitionPayable = new Payables;
                                $tuitionPayable->id = $payableId;
                                $tuitionPayable->StudentId = $student->id;
                                $tuitionPayable->PaymentFor = 'Tuition Fee for ' . ($sy != null ? $sy->SchoolYear : '(no school year declared)');
                                $tuitionPayable->Category = 'Tuition Fees';
                                $tuitionPayable->SchoolYear = $sy->SchoolYear;
                                $tuitionPayable->ClassId = $classId;

                                if ($baseTuition != null) {
                                    // copy base tuition fee if declared in classes
                                    $tuitionPayable->Payable = $baseTuition;
                                    $tuitionPayable->AmountPayable = $baseTuition;
                                    $tuitionPayable->Balance = $baseTuition;
                                } else {
                                    // get tuition per subject if not declared in classes
                                    $totalSubjectTuition = DB::table('SubjectClasses')
                                        ->leftJoin('Subjects', 'SubjectClasses.SubjectId', '=', 'Subjects.id')
                                        ->whereRaw("SubjectClasses.ClassRepoId='" . $classRepo->id . "'")
                                        ->select(
                                            DB::raw("SUM(Subjects.CourseFee) AS Total")
                                        )
                                        ->first();

                                    if ($totalSubjectTuition != null) {
                                        $tuitionPayable->Payable = $totalSubjectTuition->Total;
                                        $tuitionPayable->AmountPayable = $totalSubjectTuition->Total;
                                        $tuitionPayable->Balance = $totalSubjectTuition->Total;
                                    } else {
                                        $tuitionPayable->Payable = 0.0;
                                        $tuitionPayable->AmountPayable = 0.0;
                                        $tuitionPayable->Balance = 0.0;
                                    }
                                }

                                // create payable tuition inclusion
                                $tuitionInclusions = TuitionInclusions::where('ClassRepoId', $classRepo->id)
                                    ->where('FromSchool', $student->FromSchool != null ? $student->FromSchool : 'Private')
                                    ->get();
                                if ($tuitionInclusions != null) {
                                    foreach($tuitionInclusions as $item) {
                                        $payableInclusions = new PayableInclusions;
                                        $payableInclusions->id = IDGenerator::generateIDandRandString();
                                        $payableInclusions->PayableId = $payableId;
                                        $payableInclusions->ItemName = $item->ItemName;
                                        $payableInclusions->Amount = $item->Amount;
                                        $payableInclusions->save();
                                    }
                                }

                                // create tuitions breakdown
                                if (($class->Year == 'Grade 11' | $class->Year == 'Grade 12') && env('SENIOR_HIGH_SEM_ENROLLMENT') === 'BREAK') {
                                    // update payable, set to half per sem
                                    $tuitionPayable->Payable = $tuitionPayable->Payable > 0 ? ($tuitionPayable->Payable / 2) : 0;
                                    $tuitionPayable->AmountPayable = $tuitionPayable->AmountPayable > 0 ? ($tuitionPayable->AmountPayable / 2) : 0;
                                    $tuitionPayable->Balance = $tuitionPayable->Balance > 0 ? ($tuitionPayable->Balance / 2) : 0;

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
        
                                        $amntPayable = $tuitionPayable->AmountPayable > 0 ? ($tuitionPayable->AmountPayable / $monthsToPay) : 0;
        
                                        $tuitionBreakdown->AmountPayable = $amntPayable;
                                        $tuitionBreakdown->Payable = $amntPayable;
                                        $tuitionBreakdown->Balance = $amntPayable;
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
        
                                        $amntPayable = $tuitionPayable->AmountPayable > 0 ? ($tuitionPayable->AmountPayable / $monthsToPay) : 0;
        
                                        $tuitionBreakdown->AmountPayable = $amntPayable;
                                        $tuitionBreakdown->Payable = $amntPayable;
                                        $tuitionBreakdown->Balance = $amntPayable;
                                        $tuitionBreakdown->save();
                                    }
                                }

                                $tuitionPayable->save();

                                /**
                                 * ==========================================================================
                                 * VALIDATE SCHOLARSHIPS
                                 * ==========================================================================
                                 */
                                $scholarship = StudentScholarships::where('PayableId', $payableId)
                                    ->where('StudentId', $student->id)
                                    ->where("DeductMonthly", "Yes")
                                    ->get();
                            
                                $scholarshipAmount = 0;
                                foreach($scholarship as $item) {
                                    $item->PayableId = $payableId;
                                    $item->Notes = 'Transfered from Batch Transfer';
                                    $item->save();

                                    $scholarshipAmount += ($item->Amount != null ? floatval($item->Amount) : 0);
                                }

                                if (($class->Year == 'Grade 11' | $class->Year == 'Grade 12') && env('SENIOR_HIGH_SEM_ENROLLMENT') === 'BREAK') {
                                    $scholarshipAmount = $scholarshipAmount / 2;
                                }
                                
                                $tuitionPayable = Payables::find($payableId);

                                if ($scholarshipAmount > 0) {
                                    $tuitionPayable->DiscountAmount = $scholarshipAmount;
                                    $tuitionPayable->AmountPayable = floatval($tuitionPayable->AmountPayable) - $scholarshipAmount;
                                    $tuitionPayable->Balance = $tuitionPayable->AmountPayable;
                                    $tuitionPayable->save();

                                    // update payable tuitions breakdown
                                    $tuitionsBreakdown = TuitionsBreakdown::where('PayableId', $payableId)->whereRaw("AmountPaid IS NULL OR AmountPaid = 0")->get();
                                    if ($tuitionsBreakdown != null) {
                                        $count = count($tuitionsBreakdown);

                                        if ($count > 0) {
                                            $amountDistributable = round((floatval($scholarshipAmount) / $count), 2);
                                        
                                            foreach($tuitionsBreakdown as $item) {
                                                $item->Discount = $amountDistributable;
                                                $item->AmountPayable = floatval($item->AmountPayable) - floatval($amountDistributable);
                                                $item->Balance = floatval($item->Balance) - floatval($amountDistributable);
                                                $item->save();
                                            }
                                        }
                                    }
                                }

                                /**
                                 * ==========================================================================
                                 * CREDIT THE PREVIOUS PAYMENTS FROM THE PREVIOUS TUITION PAYABLE
                                 * ==========================================================================
                                 */
                                // update tuitions breakdown
                                if ($amountPaid > 0) {
                                    $tBreakdown = TuitionsBreakdown::where('PayableId', $payableId)->whereRaw("Balance > 0")->orderBy('ForMonth')->get();

                                    // update transactions
                                    Transactions::where('PayablesId', $payableId)
                                        ->update(['PayablesId' => $payableId]);

                                    $payment = $amountPaid;
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
                                            $item->Notes = 'Transfered payments from Batch Transfer';
                                            $item->save();
                                        }
                                    }

                                    // update payable
                                    if ($tuitionPayable != null) {
                                        $bal = $tuitionPayable != null && $tuitionPayable->Balance != null && is_numeric($tuitionPayable->Balance) ? floatval($tuitionPayable->Balance) : 0;

                                        $tuitionPayable->AmountPaid = $amountPaid;
                                        $tuitionPayable->Balance = $bal - $amountPaid;
                                        $tuitionPayable->save();
                                    }
                                }
                            }
                        }
                    }  
                }
            }
        }

        return response()->json($students, 200);
    }

    public function revalidateSubjects(Request $request) {
        $classId = $request['ClassId'];

        $students = Students::where('CurrentGradeLevel', $classId)->get();

        $class = Classes::find($classId);

        $sy = SchoolYear::find($class->SchoolYearId);

        if ($class != null) {
            if ($class->Year == 'Grade 11' | $class->Year == 'Grade 12') {
                $classRepo = ClassesRepo::where('Year', $class->Year)
                    ->where('Section', $class->Section)
                    ->where('Strand', $class->Strand)
                    ->where('Semester', $class->Semester)
                    ->first();
            } else {
                $classRepo = ClassesRepo::where('Year', $class->Year)
                    ->where('Section', $class->Section)
                    ->first();
            }
            
            if ($classRepo != null) {
                $subjectClasses = DB::table('SubjectClasses')
                    ->leftJoin('Subjects', 'SubjectClasses.SubjectId', '=', 'Subjects.id')
                    ->where('ClassRepoId', $classRepo->id)
                    ->select(
                        'SubjectClasses.*',
                        'Subjects.Teacher'
                    )
                    ->get();

                foreach($students as $item) {
                    // delete StudentSubjects fist
                    StudentSubjects::where('StudentId', $item->id)
                        ->where('ClassId', $class->id)
                        ->delete();

                    foreach($subjectClasses as $sc) {
                        $ss = new StudentSubjects;
                        $ss->id = IDGenerator::generateIDandRandString();
                        $ss->StudentId = $item->id;
                        $ss->SubjectId = $sc->SubjectId;
                        $ss->ClassId = $class->id;
                        $ss->TeacherId = $sc->Teacher;
                        $ss->save();
                    }
                }
                
                return response()->json($class, 200);
            } else {
                return response()->json('Classes Repo not found!', 404);
            }
        } else {
            return response()->json('Class not found!', 404);
        }
    }
    
    public function saveNewStudent(Request $request) {
        $studentId = $request['StudentId'];
        $classesRepoId = $request['ClassRepoId'];
        $syId = $request['SchoolYearId'];
        $subjects = $request['Subjects'];
        $type = $request['Type'];
        $semester = $request['Semester'];

        $sy = SchoolYear::find($syId);
        $classesRepo = ClassesRepo::find($classesRepoId);
        $student = Students::find($studentId);

        if ($student != null) {
            if ($classesRepo != null) {
                // check if class exists in a particular school year
                $class = Classes::where('SchoolYearId', $syId)
                    ->where('Year', $classesRepo->Year)
                    ->where('Section', $classesRepo->Section)
                    ->where('Strand', $classesRepo->Strand)
                    ->where('Semester', $semester)
                    ->first();

                // save class if not yet created
                if ($class == null) {
                    $classId = IDGenerator::generateID();
                    $class = new Classes;
                    $class->id = $classId;
                    $class->SchoolYearId = $syId;
                    $class->Year = $classesRepo->Year;
                    $class->Section = $classesRepo->Section;
                    $class->Adviser = $classesRepo->Adviser;
                    $class->Strand = $classesRepo->Strand;
                    $class->Semester = $semester;
                    $class->save();
                } else { 
                    $classId = $class->id;
                }

                // create student inside the class
                // check student first if enrolled already in class
                $enrollee = StudentClasses::where('ClassId', $classId)
                    ->where('StudentId', $studentId)
                    ->first();
                
                if ($enrollee != null) {
                    return response()->json('Student already enrolled in this class!', 403);
                } else {
                    // create enrollee/student
                    $enrollee = new StudentClasses;
                    $enrollee->id = IDGenerator::generateID();
                    $enrollee->ClassId = $classId;
                    $enrollee->StudentId = $studentId;
                    $enrollee->Status = 'Paid';
                    $enrollee->Type = $type;
                    $enrollee->Semester = $semester;
                    $enrollee->save();

                    // create subjects
                    foreach($subjects as $item) {
                        if ($item['Selected'] | $item['Selected']==='true') {
                            $studentSubjects = new StudentSubjects;
                            $studentSubjects->id = IDGenerator::generateIDandRandString();
                            $studentSubjects->StudentId = $studentId;
                            $studentSubjects->SubjectId = $item['id'];
                            $studentSubjects->ClassId = $classId;
                            $studentSubjects->TeacherId = $item['TeacherId'];
                            $studentSubjects->save();
                        }
                    }
                    
                    // update student current grade level
                    $student->CurrentGradeLevel = $classId;
                    $student->save();

                    /*
                     * ======================================================
                     * ADD TUITION FEE PAYABLES
                     * ======================================================
                     */
                    $class = Classes::find($classId);
                    if ($class != null) {
                        $classRepo = ClassesRepo::where('Year', $class->Year)
                            ->where('Section', $class->Section)
                            ->where('Strand', $class->Strand)
                            ->where('Semester', $class->Semester)
                            ->first();
                        
                        $sy = SchoolYear::find($class->SchoolYearId);

                        if ($classRepo != null) {
                            $baseTuition = $student->FromSchool === 'Private' ? $classRepo->BaseTuitionFee : ($classRepo->BaseTuitionFeePublic != null ? $classRepo->BaseTuitionFeePublic : $classRepo->BaseTuitionFee); // private is the default

                            $payableId = IDGenerator::generateIDandRandString();
                            $tuitionPayable = new Payables;
                            $tuitionPayable->id = $payableId;
                            $tuitionPayable->StudentId = $studentId;
                            $tuitionPayable->PaymentFor = 'Tuition Fee for ' . ($sy != null ? $sy->SchoolYear : '(no school year declared)');
                            $tuitionPayable->Category = 'Tuition Fees';
                            $tuitionPayable->SchoolYear = $sy->SchoolYear;
                            $tuitionPayable->ClassId = $classId;

                            if ($baseTuition != null) {
                                // copy base tuition fee if declared in classes
                                $tuitionPayable->Payable = $baseTuition;
                                $tuitionPayable->AmountPayable = $baseTuition;
                                $tuitionPayable->Balance = $baseTuition;
                            } else {
                                // get tuition per subject if not declared in classes
                                $totalSubjectTuition = DB::table('SubjectClasses')
                                    ->leftJoin('Subjects', 'SubjectClasses.SubjectId', '=', 'Subjects.id')
                                    ->whereRaw("SubjectClasses.ClassRepoId='" . $classRepo->id . "'")
                                    ->select(
                                        DB::raw("SUM(Subjects.CourseFee) AS Total")
                                    )
                                    ->first();

                                if ($totalSubjectTuition != null) {
                                    $tuitionPayable->Payable = $totalSubjectTuition->Total;
                                    $tuitionPayable->AmountPayable = $totalSubjectTuition->Total;
                                    $tuitionPayable->Balance = $totalSubjectTuition->Total;
                                } else {
                                    $tuitionPayable->Payable = 0.0;
                                    $tuitionPayable->AmountPayable = 0.0;
                                    $tuitionPayable->Balance = 0.0;
                                }
                            }

                            // create payable tuition inclusion
                            $tuitionInclusions = TuitionInclusions::where('ClassRepoId', $classRepo->id)
                                ->where('FromSchool', $student->FromSchool != null ? $student->FromSchool : 'Private')
                                ->get();
                            if ($tuitionInclusions != null) {
                                foreach($tuitionInclusions as $item) {
                                    $payableInclusions = new PayableInclusions;
                                    $payableInclusions->id = IDGenerator::generateIDandRandString();
                                    $payableInclusions->PayableId = $payableId;
                                    $payableInclusions->ItemName = $item->ItemName;
                                    $payableInclusions->Amount = $item->Amount;
                                    $payableInclusions->save();
                                }
                            }

                            // create tuitions breakdown
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
    
                                    $amntPayable = $tuitionPayable->AmountPayable > 0 ? ($tuitionPayable->AmountPayable / $monthsToPay) : 0;
    
                                    $tuitionBreakdown->AmountPayable = $amntPayable;
                                    $tuitionBreakdown->Payable = $amntPayable;
                                    $tuitionBreakdown->Balance = $amntPayable;
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
    
                                    $amntPayable = $tuitionPayable->AmountPayable > 0 ? ($tuitionPayable->AmountPayable / $monthsToPay) : 0;
    
                                    $tuitionBreakdown->AmountPayable = $amntPayable;
                                    $tuitionBreakdown->Payable = $amntPayable;
                                    $tuitionBreakdown->Balance = $amntPayable;
                                    $tuitionBreakdown->save();
                                }
                            }

                            $tuitionPayable->save();
                        }
                    }
                }
            } else {
                return response()->json('Class repository not found!', 404);
            }
        } else {
            return response()->json('Student not found!', 404);
        }
    }

    public function getClassesRepos(Request $request) {
        return response()->json(ClassesRepo::orderBy('Year')->get(), 200);
    }

    public function markEscMultiple(Request $request) {
        $students = $request['Students'];
        $option = $request['Option'];

        foreach($students as $item) {
            Students::where('id', $item['id'])
                ->update(['ESCScholar' => $option]);
        }

        return response()->json('ok', 200);
    }
    
    public function markFromSchoolMultiple(Request $request) {
        $students = $request['Students'];
        $school = $request['School'];

        foreach($students as $item) {
            Students::where('id', $item['id'])
                ->update(['FromSchool' => $school]);
        }

        return response()->json('ok', 200);
    }

    public function getMiscellaneousToTuitionsData(Request $request) {
        $classId = $request['ClassId'];

        $data =  DB::table('StudentClasses')
            ->leftJoin('Students', DB::raw("TRY_CAST(StudentClasses.StudentId AS VARCHAR(100))"), '=', DB::raw("TRY_CAST(Students.id AS VARCHAR(100))"))
            ->whereRaw("StudentClasses.ClassId='" . $classId . "'")
            ->whereRaw("Students.Status IS NULL")
            ->select(
                'Students.*',
                'StudentClasses.Status as EnrollmentStatus',
                'StudentClasses.id as StudentClassId',
                DB::raw("(SELECT SUM(TRY_CAST(td.Amount AS DECIMAL(13,2))) FROM TransactionDetails td
                        LEFT JOIN Transactions t ON t.id=td.TransactionsId
                    WHERE t.StudentId=Students.id AND td.Particulars LIKE '%Tuition Fee%' AND t.Status IS NULL) AS TuitionMiscPayable")
            )
            ->orderBy('Students.LastName')
            ->get();

        return response()->json($data, 200);
    }
}
