<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePayableUpdateLogsRequest;
use App\Http\Requests\UpdatePayableUpdateLogsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PayableUpdateLogsRepository;
use Illuminate\Http\Request;
use Flash;

class PayableUpdateLogsController extends AppBaseController
{
    /** @var PayableUpdateLogsRepository $payableUpdateLogsRepository*/
    private $payableUpdateLogsRepository;

    public function __construct(PayableUpdateLogsRepository $payableUpdateLogsRepo)
    {
        $this->middleware('auth');
        $this->payableUpdateLogsRepository = $payableUpdateLogsRepo;
    }

    /**
     * Display a listing of the PayableUpdateLogs.
     */
    public function index(Request $request)
    {
        $payableUpdateLogs = $this->payableUpdateLogsRepository->paginate(10);

        return view('payable_update_logs.index')
            ->with('payableUpdateLogs', $payableUpdateLogs);
    }

    /**
     * Show the form for creating a new PayableUpdateLogs.
     */
    public function create()
    {
        return view('payable_update_logs.create');
    }

    /**
     * Store a newly created PayableUpdateLogs in storage.
     */
    public function store(CreatePayableUpdateLogsRequest $request)
    {
        $input = $request->all();

        $payableUpdateLogs = $this->payableUpdateLogsRepository->create($input);

        Flash::success('Payable Update Logs saved successfully.');

        return redirect(route('payableUpdateLogs.index'));
    }

    /**
     * Display the specified PayableUpdateLogs.
     */
    public function show($id)
    {
        $payableUpdateLogs = $this->payableUpdateLogsRepository->find($id);

        if (empty($payableUpdateLogs)) {
            Flash::error('Payable Update Logs not found');

            return redirect(route('payableUpdateLogs.index'));
        }

        return view('payable_update_logs.show')->with('payableUpdateLogs', $payableUpdateLogs);
    }

    /**
     * Show the form for editing the specified PayableUpdateLogs.
     */
    public function edit($id)
    {
        $payableUpdateLogs = $this->payableUpdateLogsRepository->find($id);

        if (empty($payableUpdateLogs)) {
            Flash::error('Payable Update Logs not found');

            return redirect(route('payableUpdateLogs.index'));
        }

        return view('payable_update_logs.edit')->with('payableUpdateLogs', $payableUpdateLogs);
    }

    /**
     * Update the specified PayableUpdateLogs in storage.
     */
    public function update($id, UpdatePayableUpdateLogsRequest $request)
    {
        $payableUpdateLogs = $this->payableUpdateLogsRepository->find($id);

        if (empty($payableUpdateLogs)) {
            Flash::error('Payable Update Logs not found');

            return redirect(route('payableUpdateLogs.index'));
        }

        $payableUpdateLogs = $this->payableUpdateLogsRepository->update($request->all(), $id);

        Flash::success('Payable Update Logs updated successfully.');

        return redirect(route('payableUpdateLogs.index'));
    }

    /**
     * Remove the specified PayableUpdateLogs from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $payableUpdateLogs = $this->payableUpdateLogsRepository->find($id);

        if (empty($payableUpdateLogs)) {
            Flash::error('Payable Update Logs not found');

            return redirect(route('payableUpdateLogs.index'));
        }

        $this->payableUpdateLogsRepository->delete($id);

        Flash::success('Payable Update Logs deleted successfully.');

        return redirect(route('payableUpdateLogs.index'));
    }
}
