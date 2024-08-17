<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStudentsRequest;
use App\Http\Requests\UpdateStudentsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\StudentsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Students;
use App\Models\StudentClasses;
use App\Models\Classes;
use App\Models\Payables;
use App\Models\PayableInclusions;
use App\Models\StudentScholarships;
use Flash;

class StudentsController extends AppBaseController
{
    /** @var StudentsRepository $studentsRepository*/
    private $studentsRepository;

    public function __construct(StudentsRepository $studentsRepo)
    {
        $this->middleware('auth');
        $this->studentsRepository = $studentsRepo;
    }

    /**
     * Display a listing of the Students.
     */
    public function index(Request $request)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'view students'])) {
            return view('students.index');
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Show the form for creating a new Students.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created Students in storage.
     */
    public function store(CreateStudentsRequest $request)
    {
        $input = $request->all();

        $students = $this->studentsRepository->create($input);

        // Flash::success('Students saved successfully.');

        // return redirect(route('students.index'));
        return response()->json($students, 200);
    }

    /**
     * Display the specified Students.
     */
    public function show($id)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'view student details'])) {
            return view('students.show', [
                'id'  => $id,
            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Show the form for editing the specified Students.
     */
    public function edit($id)
    {
        $students = $this->studentsRepository->find($id);

        if (empty($students)) {
            Flash::error('Students not found');

            return redirect(route('students.index'));
        }

        return view('students.edit')->with('students', $students);
    }

    /**
     * Update the specified Students in storage.
     */
    public function update($id, UpdateStudentsRequest $request)
    {
        $students = $this->studentsRepository->find($id);

        if (empty($students)) {
            Flash::error('Students not found');

            return redirect(route('students.index'));
        }

        $students = $this->studentsRepository->update($request->all(), $id);

        Flash::success('Students updated successfully.');

        return redirect(route('students.index'));
    }

    /**
     * Remove the specified Students from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $students = $this->studentsRepository->find($id);

        if (empty($students)) {
            Flash::error('Students not found');

            return redirect(route('students.index'));
        }

        $this->studentsRepository->delete($id);

        StudentClasses::where('StudentId', $id)->delete();
        Payables::where('StudentId', $id)->delete();
        StudentScholarships::where('StudentId', $id)->delete();

        // Flash::success('Students deleted successfully.');

        // return redirect(route('students.index'));
        return response()->json($students, 200);
    }

    public function newStudent(Request $request) {
        if (Auth::user()->hasAnyPermission(['god permission', 'enroll student details'])) {
            return view('/students/new_student', [

            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    public function saveStudent(CreateStudentsRequest $request)
    {
        $input = $request->all();

        $students = $this->studentsRepository->create($input);

        // Flash::success('Students saved successfully.');

        // return redirect(route('students.index'));
        return response()->json($students, 200);
    }

    public function getStudent(Request $request) {
        $id = $request['id'];

        $student = DB::table('Students')
            ->leftJoin('Towns', DB::raw("TRY_CAST(Students.Town AS VARCHAR(100))"), '=', DB::raw("TRY_CAST(Towns.id AS VARCHAR(100))"))
            ->leftJoin('Barangays', DB::raw("TRY_CAST(Students.Barangay AS VARCHAR(100))"), '=', DB::raw("TRY_CAST(Barangays.id AS VARCHAR(100))"))
            ->whereRaw("Students.id='" . $id . "'")
            ->select('Students.*',
                'Towns.Town as TownSpelled',
                'Barangays.Barangay as BarangaySpelled')
            ->first();

        return response()->json($student, 200);
    }

    public function searchStudentsPaginated(Request $request) {
        $params = $request['SearchParams'];

        if ($params != null) {
            $data = DB::table('Students')
            ->leftJoin('Towns', 'Students.Town', '=', 'Towns.id')
            ->leftJoin('Barangays', 'Students.Barangay', '=', 'Barangays.id')
            ->leftJoin('Classes', 'Students.CurrentGradeLevel', '=', 'Classes.id')
            ->whereRaw("Students.FirstName LIKE '%" . $params . "%' OR Students.LastName LIKE '%" . $params . "%' OR Students.MiddleName LIKE '%" . $params . "%' OR 
                (Students.FirstName + ' ' + Students.LastName) LIKE '%" . $params . "%' OR (Students.LastName + ', ' + Students.FirstName) LIKE '%" . $params . "%' OR 
                (Students.FirstName + ' ' + Students.MiddleName + ' ' + Students.LastName) LIKE '%" . $params . "%' OR Students.id LIKE '%" . $params . "%'")
            ->select('Students.*',
                'Towns.Town as TownSpelled',
                'Barangays.Barangay as BarangaySpelled',
                'Classes.Year',
                'Classes.Section',
            )
            ->orderBy('Students.FirstName')
            ->paginate(15);
        } else {
            $data = DB::table('Students')
                ->leftJoin('Towns', 'Students.Town', '=', 'Towns.id')
                ->leftJoin('Barangays', 'Students.Barangay', '=', 'Barangays.id')
                ->leftJoin('Classes', 'Students.CurrentGradeLevel', '=', 'Classes.id')
                ->select('Students.*',
                    'Towns.Town as TownSpelled',
                    'Barangays.Barangay as BarangaySpelled',
                    'Classes.Year',
                    'Classes.Section',
                )
                ->orderBy('Students.FirstName')
                ->paginate(15);
        }

        return response()->json($data, 200);
    }

    public function getStudentDetails(Request $request) {
        $id = $request['StudentId'];
        
        $student = DB::table('Students')
            ->leftJoin('Towns', 'Students.Town', '=', 'Towns.id')
            ->leftJoin('Barangays', 'Students.Barangay', '=', 'Barangays.id')
            ->leftJoin(DB::raw("Towns tp"), DB::raw("TRY_CAST(Students.PermanentTown AS VARCHAR(100))"), '=', DB::raw("TRY_CAST(tp.id AS VARCHAR(100))"))
            ->leftJoin(DB::raw("Barangays bp"), DB::raw("TRY_CAST(Students.PermanentBarangay AS VARCHAR(100))"), '=', DB::raw("TRY_CAST(bp.id AS VARCHAR(100))"))
            ->leftJoin('Classes', 'Students.CurrentGradeLevel', '=', 'Classes.id')
            ->whereRaw("Students.id='" . $id . "'")
            ->select('Students.*',
                'Towns.Town AS TownSpelled',
                'Barangays.Barangay AS BarangaySpelled',
                'tp.Town AS TownSpelledPermanent',
                'bp.Barangay AS BarangaySpelledPermanent',
                'Classes.Year',
                'Classes.Section',
                'Classes.Semester',
                'Classes.Strand',
            )
            ->first();

        if ($student->CurrentGradeLevel != null) {
            $subjects = DB::table('StudentSubjects')
                ->leftJoin('Subjects', 'StudentSubjects.SubjectId', '=', 'Subjects.id')
                ->leftJoin('Teachers', 'Subjects.Teacher', '=', 'Teachers.id')
                ->whereRaw("StudentSubjects.ClassId='" . $student->CurrentGradeLevel . "' AND StudentSubjects.StudentId='" . $student->id . "'")
                ->select(
                    'Subjects.*',
                    'StudentSubjects.id AS StudentSubjectId',
                    'StudentSubjects.FirstGradingGrade',
                    'StudentSubjects.SecondGradingGrade',
                    'StudentSubjects.ThirdGradingGrade',
                    'StudentSubjects.FourthGradingGrade',
                    'StudentSubjects.AverageGrade',
                    'Teachers.Fullname AS TeacherName'
                )
                ->orderBy('Subjects.Subject')
                ->get();
        } else {
            $subjects = [];
        }

        $tuitionPayables = Payables::where('StudentId', $student->id)
            ->where('Category', 'Tuition Fees')
            ->orderBy('created_at')
            ->get();

        $otherPayables = DB::table('Payables')
            ->whereRaw("Category NOT IN ('Tuition Fees') AND StudentId='" . $student->id ."'")
            ->orderBy('Category')
            ->get();

        $scholarships = DB::table('StudentScholarships')
            ->leftJoin('Scholarships', 'StudentScholarships.ScholarshipId', '=', 'Scholarships.id')
            ->where('StudentScholarships.StudentId', $student->id)
            ->select('StudentScholarships.*', 'Scholarships.Scholarship')
            ->orderBy('StudentScholarships.SchoolYear')
            ->orderByDesc('StudentScholarships.created_at')
            ->get();

        $data = [
            'StudentDetails' => $student,
            'Subjects' => $subjects,
            'TuitionPayables' => $tuitionPayables,
            'OtherPayables' => $otherPayables,
            'Scholarships' => $scholarships,
        ];

        return response()->json($data, 200);
    }

    public function editStudent($studentId, $from) {
        if (Auth::user()->hasAnyPermission(['god permission', 'edit student details'])) {
            return view('/students/edit_student', [
                'studentId' => $studentId,
                'from' => $from,
            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    public function updateStudent(UpdateStudentsRequest $request) {
        $students = $this->studentsRepository->update($request->all(), $request['id']);

        return response()->json($students, 200);
    }

    public function getStudentClassDetails(Request $request) {
        $studentId = $request['StudentId'];

        $student = Students::find($studentId);
        $data['Student'] = $student;

        if ($student != null && $student->CurrentGradeLevel != null) {
            $class = Classes::find($student->CurrentGradeLevel);
            $data['Class'] = $class;

            if ($class != null) {
                $subjects = DB::table('StudentSubjects')
                    ->leftJoin('Subjects', 'StudentSubjects.SubjectId', '=', 'Subjects.id')
                    ->leftJoin('Teachers', 'StudentSubjects.TeacherId', '=', 'Teachers.id')
                    ->whereRaw("StudentSubjects.ClassId='" . $class->id . "' AND StudentSubjects.StudentId='" . $studentId . "'")
                    ->select(
                        'Subjects.*',
                        'Teachers.FullName',
                        'StudentSubjects.id AS StudentSubjectId'
                    )
                    ->orderBy('Subjects.Subject')
                    ->get();

                $data['Subjects'] = $subjects;
            } else {
                $data['Subjects'] = [];
            }
        } else {
            $data['Class'] = [];
            $data['Subjects'] = [];
        }

        return response()->json($data, 200);
    }

    public function guestView($id) {
        if (Auth::user()->hasAnyPermission(['god permission', 'view student details'])) {
            return view('/students/guest_view', [
                'id'  => $id,
            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    public function printStudents($classId) {
        $class = DB::table('Classes')
            ->whereRaw("id='" . $classId . "'")
            ->first();

        $male =  DB::table('StudentClasses')
            ->leftJoin('Students', 'StudentClasses.StudentId', '=', 'Students.id')
            ->leftJoin('Towns', 'Students.Town', '=', 'Towns.id')
            ->leftJoin('Barangays', 'Students.Barangay', '=', 'Barangays.id')
            ->whereRaw("StudentClasses.ClassId='" . $classId . "' AND Gender='Male'")
            ->select(
                'Students.*',
                'Towns.Town AS TownSpelled',
                'Barangays.Barangay AS BarangaySpelled',
                'StudentClasses.Status as EnrollmentStatus'
            )
            ->orderBy('Students.LastName')
            ->get();

        $female =  DB::table('StudentClasses')
            ->leftJoin('Students', 'StudentClasses.StudentId', '=', 'Students.id')
            ->leftJoin('Towns', 'Students.Town', '=', 'Towns.id')
            ->leftJoin('Barangays', 'Students.Barangay', '=', 'Barangays.id')
            ->whereRaw("StudentClasses.ClassId='" . $classId . "' AND Gender='Female'")
            ->select(
                'Students.*',
                'Towns.Town AS TownSpelled',
                'Barangays.Barangay AS BarangaySpelled',
                'StudentClasses.Status as EnrollmentStatus'
            )
            ->orderBy('Students.LastName')
            ->get();

        return view('/students/print_students', [
            'class' => $class, 
            'male' => $male,
            'female' => $female,
        ]);
    }

    public function updateStatus(Request $request) {
        $id = $request['id'];
        $status = $request['Status'];

        $student = Students::find($id);

        if ($student != null) {
            $student->Status = $status;
            $student->save();
        }

        return response()->json('ok', 200);
    }
    
    public function printInactiveStudents($classId) {
        $class = DB::table('Classes')
            ->whereRaw("id='" . $classId . "'")
            ->first();

        $students =  DB::table('StudentClasses')
            ->leftJoin('Students', 'StudentClasses.StudentId', '=', 'Students.id')
            ->leftJoin('Towns', 'Students.Town', '=', 'Towns.id')
            ->leftJoin('Barangays', 'Students.Barangay', '=', 'Barangays.id')
            ->whereRaw("StudentClasses.ClassId='" . $classId . "'")
            ->whereRaw("Students.Status IS NOT NULL")
            ->select(
                'Students.*',
                'Towns.Town AS TownSpelled',
                'Barangays.Barangay AS BarangaySpelled',
                'StudentClasses.Status as EnrollmentStatus'
            )
            ->orderBy('Students.Status')
            ->orderBy('Students.LastName')
            ->get();

        return view('/students/print_inactive_students', [
            'class' => $class, 
            'students' => $students,
        ]);
    }

    public function addNew(Request $request) {
        if (Auth::user()->hasAnyPermission(['god permission', 'enroll student details'])) {
            return view('/students/add_new');
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        } 
    }
    
    public function addNewToClass($studentId) {
        if (Auth::user()->hasAnyPermission(['god permission', 'enroll student class'])) {
            return view('/students/add_new_to_class', [
                'studentId' => $studentId
            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    public function markEsc(Request $request) {
        $id = $request['id'];
        $escScholar = $request['ESCScholar'];

        $student = Students::find($id);

        if ($student != null) {
            $student->ESCScholar = $escScholar;
            $student->save();
        }

        return response()->json($student, 200);
    }
}
