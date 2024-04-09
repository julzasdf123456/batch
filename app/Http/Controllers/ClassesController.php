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
        $classes = $this->classesRepository->find($id);

        if (empty($classes)) {
            Flash::error('Classes not found');

            return redirect(route('classes.index'));
        }

        return view('classes.show', [
            'class' => $classes,
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
                $classId = IDGenerator::generateID();
                if ($class == null) {
                    $class = new Classes;
                    $class->id = $classId;
                    $class->SchoolYearId = $syId;
                    $class->Year = $classesRepo->Year;
                    $class->Section = $classesRepo->Section;
                    $class->Adviser = $classesRepo->Adviser;
                    $class->save();
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
                    $enrollee->save();

                    // create payables
                    $payable = new Payables;
                    $payable->id = IDGenerator::generateID();
                    $payable->StudentId = $studentId;
                    $payable->AmountPayable = 700.00;
                    $payable->PaymentFor = 'Enrollment Fees for ' . $sy->SchoolYear;
                    $payable->Category = 'Enrollment';
                    $payable->Balance = 700.00;
                    $payable->save();

                    // create subjects
                    foreach($subjects as $item) {
                        if ($item['Selected'] | $item['Selected']==='true') {
                            $studentSubjects = new StudentSubjects;
                            $studentSubjects->id = IDGenerator::generateIDandRandString();
                            $studentSubjects->StudentId = $studentId;
                            $studentSubjects->SubjectId = $item['id'];
                            $studentSubjects->ClassId = $class->id;
                            $studentSubjects->save();
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
}
