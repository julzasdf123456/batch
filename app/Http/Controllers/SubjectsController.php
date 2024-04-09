<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubjectsRequest;
use App\Http\Requests\UpdateSubjectsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\SubjectsRepository;
use Illuminate\Http\Request;
use Flash;

class SubjectsController extends AppBaseController
{
    /** @var SubjectsRepository $subjectsRepository*/
    private $subjectsRepository;

    public function __construct(SubjectsRepository $subjectsRepo)
    {
        $this->middleware('auth');
        $this->subjectsRepository = $subjectsRepo;
    }

    /**
     * Display a listing of the Subjects.
     */
    public function index(Request $request)
    {
        $subjects = $this->subjectsRepository->paginate(10);

        return view('subjects.index')
            ->with('subjects', $subjects);
    }

    /**
     * Show the form for creating a new Subjects.
     */
    public function create()
    {
        return view('subjects.create');
    }

    /**
     * Store a newly created Subjects in storage.
     */
    public function store(CreateSubjectsRequest $request)
    {
        $input = $request->all();

        $subjects = $this->subjectsRepository->create($input);

        Flash::success('Subjects saved successfully.');

        return redirect(route('subjects.index'));
    }

    /**
     * Display the specified Subjects.
     */
    public function show($id)
    {
        $subjects = $this->subjectsRepository->find($id);

        if (empty($subjects)) {
            Flash::error('Subjects not found');

            return redirect(route('subjects.index'));
        }

        return view('subjects.show')->with('subjects', $subjects);
    }

    /**
     * Show the form for editing the specified Subjects.
     */
    public function edit($id)
    {
        $subjects = $this->subjectsRepository->find($id);

        if (empty($subjects)) {
            Flash::error('Subjects not found');

            return redirect(route('subjects.index'));
        }

        return view('subjects.edit')->with('subjects', $subjects);
    }

    /**
     * Update the specified Subjects in storage.
     */
    public function update($id, UpdateSubjectsRequest $request)
    {
        $subjects = $this->subjectsRepository->find($id);

        if (empty($subjects)) {
            Flash::error('Subjects not found');

            return redirect(route('subjects.index'));
        }

        $subjects = $this->subjectsRepository->update($request->all(), $id);

        Flash::success('Subjects updated successfully.');

        return redirect(route('subjects.index'));
    }

    /**
     * Remove the specified Subjects from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $subjects = $this->subjectsRepository->find($id);

        if (empty($subjects)) {
            Flash::error('Subjects not found');

            return redirect(route('subjects.index'));
        }

        $this->subjectsRepository->delete($id);

        Flash::success('Subjects deleted successfully.');

        return redirect(route('subjects.index'));
    }
}
