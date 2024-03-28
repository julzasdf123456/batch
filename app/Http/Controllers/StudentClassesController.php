<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStudentClassesRequest;
use App\Http\Requests\UpdateStudentClassesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\StudentClassesRepository;
use Illuminate\Http\Request;
use Flash;

class StudentClassesController extends AppBaseController
{
    /** @var StudentClassesRepository $studentClassesRepository*/
    private $studentClassesRepository;

    public function __construct(StudentClassesRepository $studentClassesRepo)
    {
        $this->middleware('auth');
        $this->studentClassesRepository = $studentClassesRepo;
    }

    /**
     * Display a listing of the StudentClasses.
     */
    public function index(Request $request)
    {
        $studentClasses = $this->studentClassesRepository->paginate(10);

        return view('student_classes.index')
            ->with('studentClasses', $studentClasses);
    }

    /**
     * Show the form for creating a new StudentClasses.
     */
    public function create()
    {
        return view('student_classes.create');
    }

    /**
     * Store a newly created StudentClasses in storage.
     */
    public function store(CreateStudentClassesRequest $request)
    {
        $input = $request->all();

        $studentClasses = $this->studentClassesRepository->create($input);

        Flash::success('Student Classes saved successfully.');

        return redirect(route('studentClasses.index'));
    }

    /**
     * Display the specified StudentClasses.
     */
    public function show($id)
    {
        $studentClasses = $this->studentClassesRepository->find($id);

        if (empty($studentClasses)) {
            Flash::error('Student Classes not found');

            return redirect(route('studentClasses.index'));
        }

        return view('student_classes.show')->with('studentClasses', $studentClasses);
    }

    /**
     * Show the form for editing the specified StudentClasses.
     */
    public function edit($id)
    {
        $studentClasses = $this->studentClassesRepository->find($id);

        if (empty($studentClasses)) {
            Flash::error('Student Classes not found');

            return redirect(route('studentClasses.index'));
        }

        return view('student_classes.edit')->with('studentClasses', $studentClasses);
    }

    /**
     * Update the specified StudentClasses in storage.
     */
    public function update($id, UpdateStudentClassesRequest $request)
    {
        $studentClasses = $this->studentClassesRepository->find($id);

        if (empty($studentClasses)) {
            Flash::error('Student Classes not found');

            return redirect(route('studentClasses.index'));
        }

        $studentClasses = $this->studentClassesRepository->update($request->all(), $id);

        Flash::success('Student Classes updated successfully.');

        return redirect(route('studentClasses.index'));
    }

    /**
     * Remove the specified StudentClasses from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $studentClasses = $this->studentClassesRepository->find($id);

        if (empty($studentClasses)) {
            Flash::error('Student Classes not found');

            return redirect(route('studentClasses.index'));
        }

        $this->studentClassesRepository->delete($id);

        Flash::success('Student Classes deleted successfully.');

        return redirect(route('studentClasses.index'));
    }
}
