<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClassSubjectParentAvgRequest;
use App\Http\Requests\UpdateClassSubjectParentAvgRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ClassSubjectParentAvgRepository;
use Illuminate\Http\Request;
use Flash;

class ClassSubjectParentAvgController extends AppBaseController
{
    /** @var ClassSubjectParentAvgRepository $classSubjectParentAvgRepository*/
    private $classSubjectParentAvgRepository;

    public function __construct(ClassSubjectParentAvgRepository $classSubjectParentAvgRepo)
    {
        $this->middleware('auth');
        $this->classSubjectParentAvgRepository = $classSubjectParentAvgRepo;
    }

    /**
     * Display a listing of the ClassSubjectParentAvg.
     */
    public function index(Request $request)
    {
        $classSubjectParentAvgs = $this->classSubjectParentAvgRepository->paginate(10);

        return view('class_subject_parent_avgs.index')
            ->with('classSubjectParentAvgs', $classSubjectParentAvgs);
    }

    /**
     * Show the form for creating a new ClassSubjectParentAvg.
     */
    public function create()
    {
        return view('class_subject_parent_avgs.create');
    }

    /**
     * Store a newly created ClassSubjectParentAvg in storage.
     */
    public function store(CreateClassSubjectParentAvgRequest $request)
    {
        $input = $request->all();

        $classSubjectParentAvg = $this->classSubjectParentAvgRepository->create($input);

        Flash::success('Class Subject Parent Avg saved successfully.');

        return redirect(route('classSubjectParentAvgs.index'));
    }

    /**
     * Display the specified ClassSubjectParentAvg.
     */
    public function show($id)
    {
        $classSubjectParentAvg = $this->classSubjectParentAvgRepository->find($id);

        if (empty($classSubjectParentAvg)) {
            Flash::error('Class Subject Parent Avg not found');

            return redirect(route('classSubjectParentAvgs.index'));
        }

        return view('class_subject_parent_avgs.show')->with('classSubjectParentAvg', $classSubjectParentAvg);
    }

    /**
     * Show the form for editing the specified ClassSubjectParentAvg.
     */
    public function edit($id)
    {
        $classSubjectParentAvg = $this->classSubjectParentAvgRepository->find($id);

        if (empty($classSubjectParentAvg)) {
            Flash::error('Class Subject Parent Avg not found');

            return redirect(route('classSubjectParentAvgs.index'));
        }

        return view('class_subject_parent_avgs.edit')->with('classSubjectParentAvg', $classSubjectParentAvg);
    }

    /**
     * Update the specified ClassSubjectParentAvg in storage.
     */
    public function update($id, UpdateClassSubjectParentAvgRequest $request)
    {
        $classSubjectParentAvg = $this->classSubjectParentAvgRepository->find($id);

        if (empty($classSubjectParentAvg)) {
            Flash::error('Class Subject Parent Avg not found');

            return redirect(route('classSubjectParentAvgs.index'));
        }

        $classSubjectParentAvg = $this->classSubjectParentAvgRepository->update($request->all(), $id);

        Flash::success('Class Subject Parent Avg updated successfully.');

        return redirect(route('classSubjectParentAvgs.index'));
    }

    /**
     * Remove the specified ClassSubjectParentAvg from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $classSubjectParentAvg = $this->classSubjectParentAvgRepository->find($id);

        if (empty($classSubjectParentAvg)) {
            Flash::error('Class Subject Parent Avg not found');

            return redirect(route('classSubjectParentAvgs.index'));
        }

        $this->classSubjectParentAvgRepository->delete($id);

        Flash::success('Class Subject Parent Avg deleted successfully.');

        return redirect(route('classSubjectParentAvgs.index'));
    }
}
