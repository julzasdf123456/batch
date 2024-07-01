<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTuitionsBreakdownRequest;
use App\Http\Requests\UpdateTuitionsBreakdownRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TuitionsBreakdownRepository;
use Illuminate\Http\Request;
use Flash;

class TuitionsBreakdownController extends AppBaseController
{
    /** @var TuitionsBreakdownRepository $tuitionsBreakdownRepository*/
    private $tuitionsBreakdownRepository;

    public function __construct(TuitionsBreakdownRepository $tuitionsBreakdownRepo)
    {
        $this->middleware('auth');
        $this->tuitionsBreakdownRepository = $tuitionsBreakdownRepo;
    }

    /**
     * Display a listing of the TuitionsBreakdown.
     */
    public function index(Request $request)
    {
        $tuitionsBreakdowns = $this->tuitionsBreakdownRepository->paginate(10);

        return view('tuitions_breakdowns.index')
            ->with('tuitionsBreakdowns', $tuitionsBreakdowns);
    }

    /**
     * Show the form for creating a new TuitionsBreakdown.
     */
    public function create()
    {
        return view('tuitions_breakdowns.create');
    }

    /**
     * Store a newly created TuitionsBreakdown in storage.
     */
    public function store(CreateTuitionsBreakdownRequest $request)
    {
        $input = $request->all();

        $tuitionsBreakdown = $this->tuitionsBreakdownRepository->create($input);

        Flash::success('Tuitions Breakdown saved successfully.');

        return redirect(route('tuitionsBreakdowns.index'));
    }

    /**
     * Display the specified TuitionsBreakdown.
     */
    public function show($id)
    {
        $tuitionsBreakdown = $this->tuitionsBreakdownRepository->find($id);

        if (empty($tuitionsBreakdown)) {
            Flash::error('Tuitions Breakdown not found');

            return redirect(route('tuitionsBreakdowns.index'));
        }

        return view('tuitions_breakdowns.show')->with('tuitionsBreakdown', $tuitionsBreakdown);
    }

    /**
     * Show the form for editing the specified TuitionsBreakdown.
     */
    public function edit($id)
    {
        $tuitionsBreakdown = $this->tuitionsBreakdownRepository->find($id);

        if (empty($tuitionsBreakdown)) {
            Flash::error('Tuitions Breakdown not found');

            return redirect(route('tuitionsBreakdowns.index'));
        }

        return view('tuitions_breakdowns.edit')->with('tuitionsBreakdown', $tuitionsBreakdown);
    }

    /**
     * Update the specified TuitionsBreakdown in storage.
     */
    public function update($id, UpdateTuitionsBreakdownRequest $request)
    {
        $tuitionsBreakdown = $this->tuitionsBreakdownRepository->find($id);

        if (empty($tuitionsBreakdown)) {
            Flash::error('Tuitions Breakdown not found');

            return redirect(route('tuitionsBreakdowns.index'));
        }

        $tuitionsBreakdown = $this->tuitionsBreakdownRepository->update($request->all(), $id);

        Flash::success('Tuitions Breakdown updated successfully.');

        return redirect(route('tuitionsBreakdowns.index'));
    }

    /**
     * Remove the specified TuitionsBreakdown from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tuitionsBreakdown = $this->tuitionsBreakdownRepository->find($id);

        if (empty($tuitionsBreakdown)) {
            Flash::error('Tuitions Breakdown not found');

            return redirect(route('tuitionsBreakdowns.index'));
        }

        $this->tuitionsBreakdownRepository->delete($id);

        Flash::success('Tuitions Breakdown deleted successfully.');

        return redirect(route('tuitionsBreakdowns.index'));
    }
}
