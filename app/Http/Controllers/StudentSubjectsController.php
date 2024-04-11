<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStudentSubjectsRequest;
use App\Http\Requests\UpdateStudentSubjectsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\StudentSubjectsRepository;
use Illuminate\Http\Request;
use App\Models\StudentSubjects;
use Flash;

class StudentSubjectsController extends AppBaseController
{
    /** @var StudentSubjectsRepository $studentSubjectsRepository*/
    private $studentSubjectsRepository;

    public function __construct(StudentSubjectsRepository $studentSubjectsRepo)
    {
        $this->middleware('auth');
        $this->studentSubjectsRepository = $studentSubjectsRepo;
    }

    /**
     * Display a listing of the StudentSubjects.
     */
    public function index(Request $request)
    {
        $studentSubjects = $this->studentSubjectsRepository->paginate(10);

        return view('student_subjects.index')
            ->with('studentSubjects', $studentSubjects);
    }

    /**
     * Show the form for creating a new StudentSubjects.
     */
    public function create()
    {
        return view('student_subjects.create');
    }

    /**
     * Store a newly created StudentSubjects in storage.
     */
    public function store(CreateStudentSubjectsRequest $request)
    {
        $input = $request->all();

        $studentSubjects = $this->studentSubjectsRepository->create($input);

        Flash::success('Student Subjects saved successfully.');

        return redirect(route('studentSubjects.index'));
    }

    /**
     * Display the specified StudentSubjects.
     */
    public function show($id)
    {
        $studentSubjects = $this->studentSubjectsRepository->find($id);

        if (empty($studentSubjects)) {
            Flash::error('Student Subjects not found');

            return redirect(route('studentSubjects.index'));
        }

        return view('student_subjects.show')->with('studentSubjects', $studentSubjects);
    }

    /**
     * Show the form for editing the specified StudentSubjects.
     */
    public function edit($id)
    {
        $studentSubjects = $this->studentSubjectsRepository->find($id);

        if (empty($studentSubjects)) {
            Flash::error('Student Subjects not found');

            return redirect(route('studentSubjects.index'));
        }

        return view('student_subjects.edit')->with('studentSubjects', $studentSubjects);
    }

    /**
     * Update the specified StudentSubjects in storage.
     */
    public function update($id, UpdateStudentSubjectsRequest $request)
    {
        $studentSubjects = $this->studentSubjectsRepository->find($id);

        if (empty($studentSubjects)) {
            Flash::error('Student Subjects not found');

            return redirect(route('studentSubjects.index'));
        }

        $studentSubjects = $this->studentSubjectsRepository->update($request->all(), $id);

        Flash::success('Student Subjects updated successfully.');

        return redirect(route('studentSubjects.index'));
    }

    /**
     * Remove the specified StudentSubjects from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $studentSubjects = $this->studentSubjectsRepository->find($id);

        if (empty($studentSubjects)) {
            Flash::error('Student Subjects not found');

            return redirect(route('studentSubjects.index'));
        }

        $this->studentSubjectsRepository->delete($id);

        Flash::success('Student Subjects deleted successfully.');

        return redirect(route('studentSubjects.index'));
    }

    public function updateGrade(Request $request) {
        $id = $request['id'];
        $grade = $request['Grade'];
        $finalGrade = $request['FinalGrade'];
        $gradePos = $request['GradePosition'];

        $subject = StudentSubjects::find($id);

        if ($subject != null) {
            if ($gradePos === '1' | $gradePos == 1) {
                $subject->FirstGradingGrade = $grade;
            } elseif ($gradePos === '2' | $gradePos == 2) {
                $subject->SecondGradingGrade = $grade;
            } elseif ($gradePos === '3' | $gradePos == 3) {
                $subject->ThirdGradingGrade = $grade;
            } elseif ($gradePos === '4' | $gradePos == 4) {
                $subject->FourthGradingGrade = $grade;
            } elseif ($gradePos === '0' | $gradePos == 0) {
                $subject->AverageGrade = $finalGrade;
            }

            $subject->AverageGrade = $finalGrade;
            $subject->save();
        }

        return response()->json($subject, 200);
    }
}
