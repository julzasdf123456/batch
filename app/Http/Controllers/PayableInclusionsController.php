<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePayableInclusionsRequest;
use App\Http\Requests\UpdatePayableInclusionsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PayableInclusionsRepository;
use Illuminate\Http\Request;
use Flash;

class PayableInclusionsController extends AppBaseController
{
    /** @var PayableInclusionsRepository $payableInclusionsRepository*/
    private $payableInclusionsRepository;

    public function __construct(PayableInclusionsRepository $payableInclusionsRepo)
    {
        $this->middleware('auth');
        $this->payableInclusionsRepository = $payableInclusionsRepo;
    }

    /**
     * Display a listing of the PayableInclusions.
     */
    public function index(Request $request)
    {
        $payableInclusions = $this->payableInclusionsRepository->paginate(10);

        return view('payable_inclusions.index')
            ->with('payableInclusions', $payableInclusions);
    }

    /**
     * Show the form for creating a new PayableInclusions.
     */
    public function create()
    {
        return view('payable_inclusions.create');
    }

    /**
     * Store a newly created PayableInclusions in storage.
     */
    public function store(CreatePayableInclusionsRequest $request)
    {
        $input = $request->all();

        $payableInclusions = $this->payableInclusionsRepository->create($input);

        Flash::success('Payable Inclusions saved successfully.');

        return redirect(route('payableInclusions.index'));
    }

    /**
     * Display the specified PayableInclusions.
     */
    public function show($id)
    {
        $payableInclusions = $this->payableInclusionsRepository->find($id);

        if (empty($payableInclusions)) {
            Flash::error('Payable Inclusions not found');

            return redirect(route('payableInclusions.index'));
        }

        return view('payable_inclusions.show')->with('payableInclusions', $payableInclusions);
    }

    /**
     * Show the form for editing the specified PayableInclusions.
     */
    public function edit($id)
    {
        $payableInclusions = $this->payableInclusionsRepository->find($id);

        if (empty($payableInclusions)) {
            Flash::error('Payable Inclusions not found');

            return redirect(route('payableInclusions.index'));
        }

        return view('payable_inclusions.edit')->with('payableInclusions', $payableInclusions);
    }

    /**
     * Update the specified PayableInclusions in storage.
     */
    public function update($id, UpdatePayableInclusionsRequest $request)
    {
        $payableInclusions = $this->payableInclusionsRepository->find($id);

        if (empty($payableInclusions)) {
            Flash::error('Payable Inclusions not found');

            return redirect(route('payableInclusions.index'));
        }

        $payableInclusions = $this->payableInclusionsRepository->update($request->all(), $id);

        Flash::success('Payable Inclusions updated successfully.');

        return redirect(route('payableInclusions.index'));
    }

    /**
     * Remove the specified PayableInclusions from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $payableInclusions = $this->payableInclusionsRepository->find($id);

        if (empty($payableInclusions)) {
            Flash::error('Payable Inclusions not found');

            return redirect(route('payableInclusions.index'));
        }

        $this->payableInclusionsRepository->delete($id);

        Flash::success('Payable Inclusions deleted successfully.');

        return redirect(route('payableInclusions.index'));
    }
}
