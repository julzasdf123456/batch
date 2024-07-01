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
        return view('/student_scholarships/scholarship_wizzard', [
            'id' => $id,
            'from' => $from,
        ]);
    }

    public function getAvailableSYPayables(Request $request) {
        $studentId = $request['StudentId'];

        $data = DB::table('Payables')
            ->whereRaw("StudentId='" . $studentId . "' AND Category='Tuition Fees' AND Balance > 0")
            ->select('*')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($data, 200);
    }

    public function getGrants(Request $request) {
        return response()->json(Scholarships::orderBy('Scholarship')->get(), 200);
    }
}
