<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTuitionInclusionsRequest;
use App\Http\Requests\UpdateTuitionInclusionsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TuitionInclusionsRepository;
use Illuminate\Http\Request;
use App\Models\ClassesRepo;
use Flash;

class TuitionInclusionsController extends AppBaseController
{
    /** @var TuitionInclusionsRepository $tuitionInclusionsRepository*/
    private $tuitionInclusionsRepository;

    public function __construct(TuitionInclusionsRepository $tuitionInclusionsRepo)
    {
        $this->middleware('auth');
        $this->tuitionInclusionsRepository = $tuitionInclusionsRepo;
    }

    /**
     * Display a listing of the TuitionInclusions.
     */
    public function index(Request $request)
    {
        $tuitionInclusions = $this->tuitionInclusionsRepository->paginate(10);

        return view('tuition_inclusions.index')
            ->with('tuitionInclusions', $tuitionInclusions);
    }

    /**
     * Show the form for creating a new TuitionInclusions.
     */
    public function create()
    {
        return view('tuition_inclusions.create');
    }

    /**
     * Store a newly created TuitionInclusions in storage.
     */
    public function store(CreateTuitionInclusionsRequest $request)
    {
        $input = $request->all();

        $tuitionInclusions = $this->tuitionInclusionsRepository->create($input);

        // update base tuition fee on ClassesRepo
        $classRepo = ClassesRepo::find($input['ClassRepoId']);
        if ($classRepo != null) {
            if ($input['FromSchool'] === 'Private') {
                $baseTuition = $classRepo->BaseTuitionFee != null ? floatval($classRepo->BaseTuitionFee) : 0;
                $inclusionAmount = isset($input['Amount']) ? floatval($input['Amount']) : 0;
                
                $classRepo->BaseTuitionFee = ($baseTuition + $inclusionAmount);
                $classRepo->save();
            } else {
                $baseTuition = $classRepo->BaseTuitionFeePublic != null ? floatval($classRepo->BaseTuitionFeePublic) : 0;
                $inclusionAmount = isset($input['Amount']) ? floatval($input['Amount']) : 0;
                
                $classRepo->BaseTuitionFeePublic = ($baseTuition + $inclusionAmount);
                $classRepo->save();
            }
        }

        // Flash::success('Tuition Inclusions saved successfully.');

        // return redirect(route('tuitionInclusions.index'));
        return response()->json($tuitionInclusions, 200);
    }

    /**
     * Display the specified TuitionInclusions.
     */
    public function show($id)
    {
        $tuitionInclusions = $this->tuitionInclusionsRepository->find($id);

        if (empty($tuitionInclusions)) {
            Flash::error('Tuition Inclusions not found');

            return redirect(route('tuitionInclusions.index'));
        }

        return view('tuition_inclusions.show')->with('tuitionInclusions', $tuitionInclusions);
    }

    /**
     * Show the form for editing the specified TuitionInclusions.
     */
    public function edit($id)
    {
        $tuitionInclusions = $this->tuitionInclusionsRepository->find($id);

        if (empty($tuitionInclusions)) {
            Flash::error('Tuition Inclusions not found');

            return redirect(route('tuitionInclusions.index'));
        }

        return view('tuition_inclusions.edit')->with('tuitionInclusions', $tuitionInclusions);
    }

    /**
     * Update the specified TuitionInclusions in storage.
     */
    public function update($id, UpdateTuitionInclusionsRequest $request)
    {
        $tuitionInclusions = $this->tuitionInclusionsRepository->find($id);

        if (empty($tuitionInclusions)) {
            Flash::error('Tuition Inclusions not found');

            return redirect(route('tuitionInclusions.index'));
        }

        $tuitionInclusions = $this->tuitionInclusionsRepository->update($request->all(), $id);

        Flash::success('Tuition Inclusions updated successfully.');

        return redirect(route('tuitionInclusions.index'));
    }

    /**
     * Remove the specified TuitionInclusions from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tuitionInclusions = $this->tuitionInclusionsRepository->find($id);

        if (empty($tuitionInclusions)) {
            Flash::error('Tuition Inclusions not found');

            return redirect(route('tuitionInclusions.index'));
        }

        // update base tuition fee on ClassesRepo
        $classRepo = ClassesRepo::find($tuitionInclusions->ClassRepoId);
        if ($classRepo != null) {
            $baseTuition = $classRepo->BaseTuitionFee != null ? floatval($classRepo->BaseTuitionFee) : 0;
            $inclusionAmount = isset($tuitionInclusions->Amount) ? floatval($tuitionInclusions->Amount) : 0;
            
            $classRepo->BaseTuitionFee = ($baseTuition - $inclusionAmount);
            $classRepo->save();
        }

        $this->tuitionInclusionsRepository->delete($id);

        // Flash::success('Tuition Inclusions deleted successfully.');

        // return redirect(route('tuitionInclusions.index'));
        return response()->json($tuitionInclusions, 200);
    }
}
