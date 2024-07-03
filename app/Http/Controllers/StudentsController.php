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
        return view('students.index');
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
        return view('students.show', [
            'id'  => $id,
        ]);
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

        Flash::success('Students deleted successfully.');

        return redirect(route('students.index'));
    }

    public function newStudent(Request $request) {
        return view('/students/new_student', [

        ]);
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
            ->leftJoin('Towns', 'Students.Town', '=', 'Towns.id')
            ->leftJoin('Barangays', 'Students.Barangay', '=', 'Barangays.id')
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
            ->leftJoin('Classes', 'Students.CurrentGradeLevel', '=', 'Classes.id')
            ->whereRaw("Students.id='" . $id . "'")
            ->select('Students.*',
                'Towns.Town AS TownSpelled',
                'Barangays.Barangay AS BarangaySpelled',
                'Classes.Year',
                'Classes.Section',
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
}
