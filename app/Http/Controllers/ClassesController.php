<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClassesRequest;
use App\Http\Requests\UpdateClassesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ClassesRepository;
use Illuminate\Http\Request;
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

        return view('classes.show')->with('classes', $classes);
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
}
