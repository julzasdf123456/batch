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
        return view('/classes/enroll', [
            'studentId' => $studentId
        ]);
    }

    public function existingStudent(Request $request) {
        return view('/classes/existing_student', [

        ]);
    }

    public function saveEnrollment(Request $request) {
        $studentId = $request['StudentId'];
        $classesRepoId = $request['ClassRepoId'];
        $syId = $request['SchoolYearId'];
        $subjects = $request['Subjects'];
        $type = $request['Type'];

        $sy = SchoolYear::find($syId);
        $classesRepo = ClassesRepo::find($classesRepoId);
        $student = Students::find($studentId);

        if ($student != null) {
            if ($classesRepo != null) {
                // check if class exists in a particular school year
                $class = Classes::where('SchoolYearId', $syId)
                    ->where('Year', $classesRepo->Year)
                    ->where('Section', $classesRepo->Section)
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
                    $enrollee->save();

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
        return view('classes.show', [
            'adviser' => $adviserId,
            'schoolYearId' => $syId,
            'classId' => $classId,
        ]);
    }
}
