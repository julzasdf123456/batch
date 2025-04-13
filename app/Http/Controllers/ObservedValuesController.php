<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateObservedValuesRequest;
use App\Http\Requests\UpdateObservedValuesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ObservedValuesRepository;
use Illuminate\Http\Request;
use Flash;

class ObservedValuesController extends AppBaseController
{
    /** @var ObservedValuesRepository $observedValuesRepository*/
    private $observedValuesRepository;

    public function __construct(ObservedValuesRepository $observedValuesRepo)
    {
        $this->middleware('auth');
        $this->observedValuesRepository = $observedValuesRepo;
    }

    /**
     * Display a listing of the ObservedValues.
     */
    public function index(Request $request)
    {
        $observedValues = $this->observedValuesRepository->paginate(10);

        return view('observed_values.index')
            ->with('observedValues', $observedValues);
    }

    /**
     * Show the form for creating a new ObservedValues.
     */
    public function create()
    {
        return view('observed_values.create');
    }

    /**
     * Store a newly created ObservedValues in storage.
     */
    public function store(CreateObservedValuesRequest $request)
    {
        $input = $request->all();

        $observedValues = $this->observedValuesRepository->create($input);

        Flash::success('Observed Values saved successfully.');

        return redirect(route('observedValues.index'));
    }

    /**
     * Display the specified ObservedValues.
     */
    public function show($id)
    {
        $observedValues = $this->observedValuesRepository->find($id);

        if (empty($observedValues)) {
            Flash::error('Observed Values not found');

            return redirect(route('observedValues.index'));
        }

        return view('observed_values.show')->with('observedValues', $observedValues);
    }

    /**
     * Show the form for editing the specified ObservedValues.
     */
    public function edit($id)
    {
        $observedValues = $this->observedValuesRepository->find($id);

        if (empty($observedValues)) {
            Flash::error('Observed Values not found');

            return redirect(route('observedValues.index'));
        }

        return view('observed_values.edit')->with('observedValues', $observedValues);
    }

    /**
     * Update the specified ObservedValues in storage.
     */
    public function update($id, UpdateObservedValuesRequest $request)
    {
        $observedValues = $this->observedValuesRepository->find($id);

        if (empty($observedValues)) {
            Flash::error('Observed Values not found');

            return redirect(route('observedValues.index'));
        }

        $observedValues = $this->observedValuesRepository->update($request->all(), $id);

        Flash::success('Observed Values updated successfully.');

        return redirect(route('observedValues.index'));
    }

    /**
     * Remove the specified ObservedValues from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $observedValues = $this->observedValuesRepository->find($id);

        if (empty($observedValues)) {
            Flash::error('Observed Values not found');

            return redirect(route('observedValues.index'));
        }

        $this->observedValuesRepository->delete($id);

        Flash::success('Observed Values deleted successfully.');

        return redirect(route('observedValues.index'));
    }
}
