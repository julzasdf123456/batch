<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeachersRequest;
use App\Http\Requests\UpdateTeachersRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TeachersRepository;
use Illuminate\Http\Request;
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
        $teachers = $this->teachersRepository->paginate(10);

        return view('teachers.index')
            ->with('teachers', $teachers);
    }

    /**
     * Show the form for creating a new Teachers.
     */
    public function create()
    {
        return view('teachers.create');
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
        $teachers = $this->teachersRepository->find($id);

        if (empty($teachers)) {
            Flash::error('Teachers not found');

            return redirect(route('teachers.index'));
        }

        return view('teachers.show')->with('teachers', $teachers);
    }

    /**
     * Show the form for editing the specified Teachers.
     */
    public function edit($id)
    {
        $teachers = $this->teachersRepository->find($id);

        if (empty($teachers)) {
            Flash::error('Teachers not found');

            return redirect(route('teachers.index'));
        }

        return view('teachers.edit')->with('teachers', $teachers);
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
        $teachers = $this->teachersRepository->find($id);

        if (empty($teachers)) {
            Flash::error('Teachers not found');

            return redirect(route('teachers.index'));
        }

        $this->teachersRepository->delete($id);

        Flash::success('Teachers deleted successfully.');

        return redirect(route('teachers.index'));
    }
}
