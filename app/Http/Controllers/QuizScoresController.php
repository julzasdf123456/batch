<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuizScoresRequest;
use App\Http\Requests\UpdateQuizScoresRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\QuizScoresRepository;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Students;
use App\Models\Subjects;
use App\Models\QuizScores;
use App\Models\StudentSubjects;
use App\Models\IDGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;

class QuizScoresController extends AppBaseController
{
    /** @var QuizScoresRepository $quizScoresRepository*/
    private $quizScoresRepository;

    public function __construct(QuizScoresRepository $quizScoresRepo)
    {
        $this->middleware('auth');
        $this->quizScoresRepository = $quizScoresRepo;
    }

    /**
     * Display a listing of the QuizScores.
     */
    public function index(Request $request)
    {
        $quizScores = $this->quizScoresRepository->paginate(10);

        return view('quiz_scores.index')
            ->with('quizScores', $quizScores);
    }

    /**
     * Show the form for creating a new QuizScores.
     */
    public function create()
    {
        return view('quiz_scores.create');
    }

    /**
     * Store a newly created QuizScores in storage.
     */
    public function store(CreateQuizScoresRequest $request)
    {
        $input = $request->all();

        $quizScores = $this->quizScoresRepository->create($input);

        Flash::success('Quiz Scores saved successfully.');

        return redirect(route('quizScores.index'));
    }

    /**
     * Display the specified QuizScores.
     */
    public function show($id)
    {
        $quizScores = $this->quizScoresRepository->find($id);

        if (empty($quizScores)) {
            Flash::error('Quiz Scores not found');

            return redirect(route('quizScores.index'));
        }

        return view('quiz_scores.show')->with('quizScores', $quizScores);
    }

    /**
     * Show the form for editing the specified QuizScores.
     */
    public function edit($id)
    {
        $quizScores = $this->quizScoresRepository->find($id);

        if (empty($quizScores)) {
            Flash::error('Quiz Scores not found');

            return redirect(route('quizScores.index'));
        }

        return view('quiz_scores.edit')->with('quizScores', $quizScores);
    }

    /**
     * Update the specified QuizScores in storage.
     */
    public function update($id, UpdateQuizScoresRequest $request)
    {
        $quizScores = $this->quizScoresRepository->find($id);

        if (empty($quizScores)) {
            Flash::error('Quiz Scores not found');

            return redirect(route('quizScores.index'));
        }

        $quizScores = $this->quizScoresRepository->update($request->all(), $id);

        // Flash::success('Quiz Scores updated successfully.');

        // return redirect(route('quizScores.index'));
        return response()->json($quizScores, 200);
    }

    /**
     * Remove the specified QuizScores from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $quizScores = $this->quizScoresRepository->find($id);

        if (empty($quizScores)) {
            Flash::error('Quiz Scores not found');

            return redirect(route('quizScores.index'));
        }

        $this->quizScoresRepository->delete($id);

        Flash::success('Quiz Scores deleted successfully.');

        return redirect(route('quizScores.index'));
    }

    public function saveQuizSheet(Request $request) {
        $classId = $request['ClassId'];
        $subjectId = $request['SubjectId'];
        $teacherId = $request['TeacherId'];
        $quizTitle = $request['QuizTitle'];
        $totalScore = $request['TotalScore'];
        $gradingPeriod = $request['GradingPeriod'];

        $students = StudentSubjects::where('ClassId', $classId)
            ->where('SubjectId', $subjectId)
            ->where('TeacherId', $teacherId)
            ->get();

        foreach($students as $item) {
            $q = new QuizScores;
            $q->id = IDGenerator::generateIDandRandString();
            $q->StudentId = $item->StudentId;
            $q->SubjectId = $subjectId;
            $q->ClassId = $classId;
            $q->TeacherId = $teacherId;
            $q->GradingPeriod = $gradingPeriod;
            $q->UserId = Auth::id();
            $q->TotalScore = $totalScore;
            $q->QuizTitle = $quizTitle;
            $q->save();
        }

        return response()->json('ok', 200);
    }

    public function getQuizHeaders(Request $request) {
        $classId = $request['ClassId'];
        $subjectId = $request['SubjectId'];
        $teacherId = $request['TeacherId'];

        $headers = DB::table('QuizScores')
            ->where('ClassId', $classId)
            ->where('SubjectId', $subjectId)
            ->where('TeacherId', $teacherId)
            ->select('QuizTitle', 'ClassId', 'SubjectId', 'TeacherId', 'GradingPeriod')
            ->groupBy('QuizTitle', 'ClassId', 'SubjectId', 'TeacherId', 'GradingPeriod')
            ->orderBy('GradingPeriod')
            ->get();

        $grades = DB::table('QuizScores')
            ->where('ClassId', $classId)
            ->where('SubjectId', $subjectId)
            ->where('TeacherId', $teacherId)
            ->select('*')
            ->get();

        return response()->json(['Headers' => $headers, 'Grades' => $grades], 200);
    }

    public function updateScore(Request $request) {
        $classId = $request['ClassId'];
        $subjectId = $request['SubjectId'];
        $teacherId = $request['TeacherId'];
        $quizTitle = $request['QuizTitle'];
        $studentId = $request['StudentId'];
        $score = $request['Score'];

        $scoreSheet = QuizScores::where('ClassId', $classId)
            ->where('SubjectId', $subjectId)
            ->where('TeacherId', $teacherId)
            ->where('QuizTitle', $quizTitle)
            ->where('StudentId', $studentId)
            ->first();

        if ($scoreSheet != null) {
            $scoreSheet->StudentScore = $score;
            $scoreSheet->save();
        }

        return response()->json($scoreSheet, 200);
    }
}
