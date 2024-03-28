<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStudentsRequest;
use App\Http\Requests\UpdateStudentsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\StudentsRepository;
use Illuminate\Http\Request;
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
        $students = $this->studentsRepository->paginate(10);

        return view('students.index')
            ->with('students', $students);
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
        $students = $this->studentsRepository->find($id);

        if (empty($students)) {
            Flash::error('Students not found');

            return redirect(route('students.index'));
        }

        return view('students.show')->with('students', $students);
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
}
