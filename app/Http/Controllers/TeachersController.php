<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeachersRequest;
use App\Http\Requests\UpdateTeachersRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TeachersRepository;
use Illuminate\Http\Request;
use App\Models\Teachers;
use App\Models\Classes;
use App\Models\StudentSubjects;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;

class TeachersController extends AppBaseController
{
    /** @var TeachersRepository $teachersRepository*/
    private $teachersRepository;

    public function __construct(TeachersRepository $teachersRepo)
    {
        $this->middleware('auth');
        $this->teachersRepository = $teachersRepo;
    }

    /**
     * Display a listing of the Teachers.
     */
    public function index(Request $request)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'view teachers'])) {
            $teachers = $this->teachersRepository->paginate(30);

            return view('teachers.index')
                ->with('teachers', $teachers);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Show the form for creating a new Teachers.
     */
    public function create()
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'create teachers'])) {
            return view('teachers.create');
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Store a newly created Teachers in storage.
     */
    public function store(CreateTeachersRequest $request)
    {
        $input = $request->all();

        $teachers = $this->teachersRepository->create($input);

        Flash::success('Teachers saved successfully.');

        return redirect(route('teachers.index'));
    }

    /**
     * Display the specified Teachers.
     */
    public function show($id)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'view teachers'])) {
            return view('teachers.show', [
                'id' => $id,
            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Show the form for editing the specified Teachers.
     */
    public function edit($id)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'edit teachers'])) {
            $teachers = $this->teachersRepository->find($id);

            if (empty($teachers)) {
                Flash::error('Teachers not found');

                return redirect(route('teachers.index'));
            }

            return view('teachers.edit')->with('teachers', $teachers);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Update the specified Teachers in storage.
     */
    public function update($id, UpdateTeachersRequest $request)
    {
        $teachers = $this->teachersRepository->find($id);

        if (empty($teachers)) {
            Flash::error('Teachers not found');

            return redirect(route('teachers.index'));
        }

        $teachers = $this->teachersRepository->update($request->all(), $id);

        Flash::success('Teachers updated successfully.');

        return redirect(route('teachers.index'));
    }

    /**
     * Remove the specified Teachers from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'delete teachers'])) {
            $teachers = $this->teachersRepository->find($id);

            if (empty($teachers)) {
                Flash::error('Teachers not found');

                return redirect(route('teachers.index'));
            }

            $this->teachersRepository->delete($id);

            Flash::success('Teachers deleted successfully.');

            return redirect(route('teachers.index'));
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    public function getTeacherData(Request $request) {
        $id = $request['id'];

        $teacher = Teachers::find($id);

        $schoolYears = DB::table('StudentSubjects')
            ->leftJoin('Classes', 'StudentSubjects.ClassId', '=', 'Classes.id')
            ->leftJoin('SchoolYear', 'Classes.SchoolYearId', '=', 'SchoolYear.id')
            ->whereRaw("StudentSubjects.TeacherId='" . $id . "'")
            ->select(
                'SchoolYear.SchoolYear',
                'SchoolYear.id',
            )
            ->groupBy('SchoolYear.SchoolYear', 'SchoolYear.id')
            ->orderByDesc('id')
            ->get();

        foreach ($schoolYears as $item) {
            $item->SubjectClasses = DB::table('StudentSubjects')
                ->leftJoin('Subjects', 'StudentSubjects.SubjectId', '=', 'Subjects.id')
                ->leftJoin('Classes', 'StudentSubjects.ClassId', '=', 'Classes.id')
                ->whereRaw("StudentSubjects.TeacherId='" . $id . "' AND Classes.SchoolYearId='" . $item->id . "'")
                ->select(
                    'StudentSubjects.ClassId',
                    'Classes.Year',
                    'Classes.Section',
                    'Classes.Strand',
                    'Classes.Semester',
                    'Subjects.id',
                    'Subjects.Subject',
                    DB::raw("'false' AS Selected")
                )
                ->groupBy(
                    'StudentSubjects.ClassId',
                    'Classes.Year',
                    'Classes.Section',
                    'Classes.Strand',
                    'Classes.Semester',
                    'Subjects.id',
                    'Subjects.Subject',
                )
                ->orderBy('Subjects.Subject')
                ->get();
        }

        $data = [
            'teacher' => $teacher,
            'schoolYears' => $schoolYears,
        ];

        return response()->json($data, 200);
    }

    public function getStudentsFromSubjectClass(Request $request) {
        $classId = $request['ClassId'];
        $teacherId = $request['TeacherId'];
        $subjectId = $request['SubjectId'];

        $data = DB::table('StudentSubjects')
            ->leftJoin('Students', 'StudentSubjects.StudentId', '=', 'Students.id')
            ->leftJoin('Classes', 'StudentSubjects.ClassId', '=', 'Classes.id')
            ->whereRaw("StudentSubjects.TeacherId='" . $teacherId . "' AND StudentSubjects.ClassId='" . $classId . "' AND StudentSubjects.SubjectId='" . $subjectId . "'")
            ->select(
                'StudentSubjects.*',
                'Students.FirstName',
                'Students.LastName',
                'Students.MiddleName',
                'Students.Suffix',
            )
            ->orderBy('Students.LastName')
            ->get();

        return response()->json($data, 200);
    }

    public function getClassDetails(Request $request) {
        $classId = $request['ClassId'];
        $teacherId = $request['TeacherId'];
        $subjectId = $request['SubjectId'];
        $syId = $request['SchoolYearId'];

        $data = [];

        $data['SchoolYear'] = DB::table('SchoolYear')
            ->where('id', $syId)
            ->first();

        $data['Class'] = DB::table('StudentSubjects')
            ->leftJoin('Subjects', 'StudentSubjects.SubjectId', '=', 'Subjects.id')
            ->leftJoin('Classes', 'StudentSubjects.ClassId', '=', 'Classes.id')
            ->whereRaw("StudentSubjects.ClassId='" . $classId . "' AND StudentSubjects.TeacherId='" . $teacherId . "' AND StudentSubjects.SubjectId='" . $subjectId . "'")
            ->select(
                'StudentSubjects.ClassId',
                'Classes.Year',
                'Classes.Section',
                'Classes.Semester',
                'Subjects.id',
                'Subjects.Subject',
                'Subjects.GradingType',
            )
            ->groupBy(
                'StudentSubjects.ClassId',
                'Classes.Year',
                'Classes.Section',
                'Classes.Semester',
                'Subjects.id',
                'Subjects.Subject',
                'Subjects.GradingType',
            )
            ->first();

        $data['MaleStudents'] = DB::table('StudentSubjects')
            ->leftJoin('Students', 'StudentSubjects.StudentId', '=', 'Students.id')
            ->leftJoin('Classes', 'StudentSubjects.ClassId', '=', 'Classes.id')
            ->leftJoin('StudentClasses', function($join) {
                $join->on('StudentClasses.ClassId', '=', 'StudentSubjects.ClassId')
                    ->on('StudentClasses.StudentId', '=', 'StudentSubjects.StudentId');
            })
            ->whereRaw("StudentSubjects.TeacherId='" . $teacherId . "' AND StudentSubjects.ClassId='" . $classId . "' AND StudentSubjects.SubjectId='" . $subjectId . "' AND Gender='Male'")
            ->whereRaw("Students.Status IS NULL")
            ->select(
                'StudentSubjects.*',
                'Students.FirstName',
                'Students.LastName',
                'Students.MiddleName',
                'Students.Suffix',
                'Students.FromSchool',
                'StudentClasses.Status',
            )
            ->orderBy('Students.LastName')
            ->get();

        $data['FemaleStudents'] = DB::table('StudentSubjects')
            ->leftJoin('Students', 'StudentSubjects.StudentId', '=', 'Students.id')
            ->leftJoin('Classes', 'StudentSubjects.ClassId', '=', 'Classes.id')
            ->leftJoin('StudentClasses', function($join) {
                $join->on('StudentClasses.ClassId', '=', 'StudentSubjects.ClassId')
                    ->on('StudentClasses.StudentId', '=', 'StudentSubjects.StudentId');
            })
            ->whereRaw("StudentSubjects.TeacherId='" . $teacherId . "' AND StudentSubjects.ClassId='" . $classId . "' AND StudentSubjects.SubjectId='" . $subjectId . "' AND Gender='Female'")
            ->whereRaw("Students.Status IS NULL")
            ->select(
                'StudentSubjects.*',
                'Students.FirstName',
                'Students.LastName',
                'Students.MiddleName',
                'Students.Suffix',
                'Students.FromSchool',
                'StudentClasses.Status',
            )
            ->orderBy('Students.LastName')
            ->get();

        return response()->json($data, 200);
    }

    public function updateGradeVisibility(Request $request) {
        $classId = $request['ClassId'];
        $teacherId = $request['TeacherId'];
        $subjectId = $request['SubjectId'];
        $visibility = $request['Visibility'];

        StudentSubjects::where('ClassId', $classId)
            ->where('TeacherId', $teacherId)
            ->where('SubjectId', $subjectId)
            ->update(['Visibility' => $visibility]);

        return response()->json('ok', 200);
    }

    public function getClassPaymentDetails(Request $request) {
        $classId = $request['ClassId'];
        $schoolYear = $request['SchoolYear'];

        $class = Classes::find($classId);
        $sy = SchoolYear::where('SchoolYear', $schoolYear)->first();
        $data = [];
        
        if ($class != null && ($class->Year === 'Grade 11' | $class->Year === 'Grade 12') && $class->Semester === '2nd' && env('SENIOR_HIGH_SEM_ENROLLMENT') === 'CONTINUOS' && env('TUITION_PROPAGATION_PRESET') === 'FLEXIBLE_ENROLLMENT_FEE') {
            // get 1st sem class
            $classFirst = Classes::where('Year', $class->Year)
                ->where('Section', $class->Section)
                ->where('Strand', $class->Strand)
                ->where('Semester', '1st')
                ->where('SchoolYearId', $sy != null ? $sy->id : null)
                ->first();

            if ($classFirst != null) {
                $data['Months'] = DB::table('TuitionsBreakdown')
                    ->leftJoin('Payables', 'Payables.id', '=', 'TuitionsBreakdown.PayableId')
                    ->whereRaw("Payables.SchoolYear='" . $schoolYear . "' AND Payables.ClassId='" . $classFirst->id . "' AND Payables.Category='Tuition Fees' AND Payables.StudentId IN (SELECT StudentId FROM StudentClasses WHERE ClassId='" . $classFirst->id . "')")
                    ->select(
                        'ForMonth'
                    )
                    ->groupBy('ForMonth')
                    ->orderBy('ForMonth')
                    ->get();

                $data['PaymentData'] = DB::table('TuitionsBreakdown')
                    ->leftJoin('Payables', 'Payables.id', '=', 'TuitionsBreakdown.PayableId')
                    ->whereRaw("Payables.SchoolYear='" . $schoolYear . "' AND Payables.ClassId='" . $classFirst->id . "' AND Payables.Category='Tuition Fees' AND Payables.StudentId IN (SELECT StudentId FROM StudentClasses WHERE ClassId='" . $classFirst->id . "')")
                    ->select(
                        'ForMonth',
                        'Payables.StudentId',
                        DB::raw("SUM(TRY_CAST(TuitionsBreakdown.AmountPayable AS DECIMAL(12,3))) AS AmountPayable"),
                        DB::raw("SUM(TRY_CAST(TuitionsBreakdown.AmountPaid AS DECIMAL(12,3))) AS AmountPaid"),
                        DB::raw("SUM(TRY_CAST(TuitionsBreakdown.Balance AS DECIMAL(12,3))) AS Balance"),
                    )
                    ->groupBy('ForMonth', 'Payables.StudentId')
                    ->orderBy('Payables.StudentId')
                    ->orderBy('ForMonth')
                    ->get();

                $data['PayableProfile'] = DB::table('Payables')
                    ->whereRaw("SchoolYear='" . $schoolYear . "' AND ClassId='" . $classFirst->id . "' AND Category='Tuition Fees' AND StudentId IN (SELECT StudentId FROM StudentClasses WHERE ClassId='" . $classFirst->id . "')")
                    ->get();
            } else {
                $data['Months'] = DB::table('TuitionsBreakdown')
                    ->leftJoin('Payables', 'Payables.id', '=', 'TuitionsBreakdown.PayableId')
                    ->whereRaw("Payables.SchoolYear='" . $schoolYear . "' AND Payables.ClassId='" . $classId . "' AND Payables.Category='Tuition Fees' AND Payables.StudentId IN (SELECT StudentId FROM StudentClasses WHERE ClassId='" . $classId . "')")
                    ->select(
                        'ForMonth'
                    )
                    ->groupBy('ForMonth')
                    ->orderBy('ForMonth')
                    ->get();

                $data['PaymentData'] = DB::table('TuitionsBreakdown')
                    ->leftJoin('Payables', 'Payables.id', '=', 'TuitionsBreakdown.PayableId')
                    ->whereRaw("Payables.SchoolYear='" . $schoolYear . "' AND Payables.ClassId='" . $classId . "' AND Payables.Category='Tuition Fees' AND Payables.StudentId IN (SELECT StudentId FROM StudentClasses WHERE ClassId='" . $classId . "')")
                    ->select(
                        'ForMonth',
                        'Payables.StudentId',
                        DB::raw("SUM(TRY_CAST(TuitionsBreakdown.AmountPayable AS DECIMAL(12,3))) AS AmountPayable"),
                        DB::raw("SUM(TRY_CAST(TuitionsBreakdown.AmountPaid AS DECIMAL(12,3))) AS AmountPaid"),
                        DB::raw("SUM(TRY_CAST(TuitionsBreakdown.Balance AS DECIMAL(12,3))) AS Balance"),
                    )
                    ->groupBy('ForMonth', 'Payables.StudentId')
                    ->orderBy('Payables.StudentId')
                    ->orderBy('ForMonth')
                    ->get();

                $data['PayableProfile'] = DB::table('Payables')
                    ->whereRaw("SchoolYear='" . $schoolYear . "' AND ClassId='" . $classId . "' AND Category='Tuition Fees' AND StudentId IN (SELECT StudentId FROM StudentClasses WHERE ClassId='" . $classId . "')")
                    ->get();
            }
        } else {
            $data['Months'] = DB::table('TuitionsBreakdown')
                ->leftJoin('Payables', 'Payables.id', '=', 'TuitionsBreakdown.PayableId')
                ->whereRaw("Payables.SchoolYear='" . $schoolYear . "' AND Payables.ClassId='" . $classId . "' AND Payables.Category='Tuition Fees' AND Payables.StudentId IN (SELECT StudentId FROM StudentClasses WHERE ClassId='" . $classId . "')")
                ->select(
                    'ForMonth'
                )
                ->groupBy('ForMonth')
                ->orderBy('ForMonth')
                ->get();

            $data['PaymentData'] = DB::table('TuitionsBreakdown')
                ->leftJoin('Payables', 'Payables.id', '=', 'TuitionsBreakdown.PayableId')
                ->whereRaw("Payables.SchoolYear='" . $schoolYear . "' AND Payables.ClassId='" . $classId . "' AND Payables.Category='Tuition Fees' AND Payables.StudentId IN (SELECT StudentId FROM StudentClasses WHERE ClassId='" . $classId . "')")
                ->select(
                    'ForMonth',
                    'Payables.StudentId',
                    DB::raw("SUM(TRY_CAST(TuitionsBreakdown.AmountPayable AS DECIMAL(12,3))) AS AmountPayable"),
                    DB::raw("SUM(TRY_CAST(TuitionsBreakdown.AmountPaid AS DECIMAL(12,3))) AS AmountPaid"),
                    DB::raw("SUM(TRY_CAST(TuitionsBreakdown.Balance AS DECIMAL(12,3))) AS Balance"),
                )
                ->groupBy('ForMonth', 'Payables.StudentId')
                ->orderBy('Payables.StudentId')
                ->orderBy('ForMonth')
                ->get();

            $data['PayableProfile'] = DB::table('Payables')
                ->whereRaw("SchoolYear='" . $schoolYear . "' AND ClassId='" . $classId . "' AND Category='Tuition Fees' AND StudentId IN (SELECT StudentId FROM StudentClasses WHERE ClassId='" . $classId . "')")
                ->get();
        }

        return response()->json($data, 200);
    }

    public function printClassPaymentDetails($classId, $schoolYearId, $subjectId) {
        return view('/my_account/print_class_payment_details', [
            'classId' => $classId,
            'schoolYearId' => $schoolYearId,
            'subjectId' => $subjectId,
        ]);
    }
}
