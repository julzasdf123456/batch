<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransactionDetailsRequest;
use App\Http\Requests\UpdateTransactionDetailsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TransactionDetailsRepository;
use Illuminate\Http\Request;
use Flash;

class TransactionDetailsController extends AppBaseController
{
    /** @var TransactionDetailsRepository $transactionDetailsRepository*/
    private $transactionDetailsRepository;

    public function __construct(TransactionDetailsRepository $transactionDetailsRepo)
    {
        $this->middleware('auth');
        $this->transactionDetailsRepository = $transactionDetailsRepo;
    }

    /**
     * Display a listing of the TransactionDetails.
     */
    public function index(Request $request)
    {
        $transactionDetails = $this->transactionDetailsRepository->paginate(10);

        return view('transaction_details.index')
            ->with('transactionDetails', $transactionDetails);
    }

    /**
     * Show the form for creating a new TransactionDetails.
     */
    public function create()
    {
        return view('transaction_details.create');
    }

    /**
     * Store a newly created TransactionDetails in storage.
     */
    public function store(CreateTransactionDetailsRequest $request)
    {
        $input = $request->all();

        $transactionDetails = $this->transactionDetailsRepository->create($input);

        Flash::success('Transaction Details saved successfully.');

        return redirect(route('transactionDetails.index'));
    }

    /**
     * Display the specified TransactionDetails.
     */
    public function show($id)
    {
        $transactionDetails = $this->transactionDetailsRepository->find($id);

        if (empty($transactionDetails)) {
            Flash::error('Transaction Details not found');

            return redirect(route('transactionDetails.index'));
        }

        return view('transaction_details.show')->with('transactionDetails', $transactionDetails);
    }

    /**
     * Show the form for editing the specified TransactionDetails.
     */
    public function edit($id)
    {
        $transactionDetails = $this->transactionDetailsRepository->find($id);

        if (empty($transactionDetails)) {
            Flash::error('Transaction Details not found');

            return redirect(route('transactionDetails.index'));
        }

        return view('transaction_details.edit')->with('transactionDetails', $transactionDetails);
    }

    /**
     * Update the specified TransactionDetails in storage.
     */
    public function update($id, UpdateTransactionDetailsRequest $request)
    {
        $transactionDetails = $this->transactionDetailsRepository->find($id);

        if (empty($transactionDetails)) {
            Flash::error('Transaction Details not found');

            return redirect(route('transactionDetails.index'));
        }

        $transactionDetails = $this->transactionDetailsRepository->update($request->all(), $id);

        Flash::success('Transaction Details updated successfully.');

        return redirect(route('transactionDetails.index'));
    }

    /**
     * Remove the specified TransactionDetails from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $transactionDetails = $this->transactionDetailsRepository->find($id);

        if (empty($transactionDetails)) {
            Flash::error('Transaction Details not found');

            return redirect(route('transactionDetails.index'));
        }

        $this->transactionDetailsRepository->delete($id);

        Flash::success('Transaction Details deleted successfully.');

        return redirect(route('transactionDetails.index'));
    }
}
