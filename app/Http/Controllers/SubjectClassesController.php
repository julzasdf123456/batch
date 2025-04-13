<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubjectClassesRequest;
use App\Http\Requests\UpdateSubjectClassesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\SubjectClassesRepository;
use Illuminate\Http\Request;
use Flash;

class SubjectClassesController extends AppBaseController
{
    /** @var SubjectClassesRepository $subjectClassesRepository*/
    private $subjectClassesRepository;

    public function __construct(SubjectClassesRepository $subjectClassesRepo)
    {
        $this->middleware('auth');
        $this->subjectClassesRepository = $subjectClassesRepo;
    }

    /**
     * Display a listing of the SubjectClasses.
     */
    public function index(Request $request)
    {
        $subjectClasses = $this->subjectClassesRepository->paginate(10);

        return view('subject_classes.index')
            ->with('subjectClasses', $subjectClasses);
    }

    /**
     * Show the form for creating a new SubjectClasses.
     */
    public function create()
    {
        return view('subject_classes.create');
    }

    /**
     * Store a newly created SubjectClasses in storage.
     */
    public function store(CreateSubjectClassesRequest $request)
    {
        $input = $request->all();

        $subjectClasses = $this->subjectClassesRepository->create($input);

        Flash::success('Subject Classes saved successfully.');

        return redirect(route('subjectClasses.index'));
    }

    /**
     * Display the specified SubjectClasses.
     */
    public function show($id)
    {
        $subjectClasses = $this->subjectClassesRepository->find($id);

        if (empty($subjectClasses)) {
            Flash::error('Subject Classes not found');

            return redirect(route('subjectClasses.index'));
        }

        return view('subject_classes.show')->with('subjectClasses', $subjectClasses);
    }

    /**
     * Show the form for editing the specified SubjectClasses.
     */
    public function edit($id)
    {
        $subjectClasses = $this->subjectClassesRepository->find($id);

        if (empty($subjectClasses)) {
            Flash::error('Subject Classes not found');

            return redirect(route('subjectClasses.index'));
        }

        return view('subject_classes.edit')->with('subjectClasses', $subjectClasses);
    }

    /**
     * Update the specified SubjectClasses in storage.
     */
    public function update($id, UpdateSubjectClassesRequest $request)
    {
        $subjectClasses = $this->subjectClassesRepository->find($id);

        if (empty($subjectClasses)) {
            Flash::error('Subject Classes not found');

            return redirect(route('subjectClasses.index'));
        }

        $subjectClasses = $this->subjectClassesRepository->update($request->all(), $id);

        Flash::success('Subject Classes updated successfully.');

        return redirect(route('subjectClasses.index'));
    }

    /**
     * Remove the specified SubjectClasses from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $subjectClasses = $this->subjectClassesRepository->find($id);

        if (empty($subjectClasses)) {
            Flash::error('Subject Classes not found');

            return redirect(route('subjectClasses.index'));
        }

        $this->subjectClassesRepository->delete($id);

        // Flash::success('Subject Classes deleted successfully.');

        // return redirect(route('subjectClasses.index'));
        return response()->json($subjectClasses, 200);
    }
}
