<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePayablesRequest;
use App\Http\Requests\UpdatePayablesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PayablesRepository;
use Illuminate\Http\Request;
use Flash;

class PayablesController extends AppBaseController
{
    /** @var PayablesRepository $payablesRepository*/
    private $payablesRepository;

    public function __construct(PayablesRepository $payablesRepo)
    {
        $this->middleware('auth');
        $this->payablesRepository = $payablesRepo;
    }

    /**
     * Display a listing of the Payables.
     */
    public function index(Request $request)
    {
        $payables = $this->payablesRepository->paginate(10);

        return view('payables.index')
            ->with('payables', $payables);
    }

    /**
     * Show the form for creating a new Payables.
     */
    public function create()
    {
        return view('payables.create');
    }

    /**
     * Store a newly created Payables in storage.
     */
    public function store(CreatePayablesRequest $request)
    {
        $input = $request->all();

        $payables = $this->payablesRepository->create($input);

        Flash::success('Payables saved successfully.');

        return redirect(route('payables.index'));
    }

    /**
     * Display the specified Payables.
     */
    public function show($id)
    {
        $payables = $this->payablesRepository->find($id);

        if (empty($payables)) {
            Flash::error('Payables not found');

            return redirect(route('payables.index'));
        }

        return view('payables.show')->with('payables', $payables);
    }

    /**
     * Show the form for editing the specified Payables.
     */
    public function edit($id)
    {
        $payables = $this->payablesRepository->find($id);

        if (empty($payables)) {
            Flash::error('Payables not found');

            return redirect(route('payables.index'));
        }

        return view('payables.edit')->with('payables', $payables);
    }

    /**
     * Update the specified Payables in storage.
     */
    public function update($id, UpdatePayablesRequest $request)
    {
        $payables = $this->payablesRepository->find($id);

        if (empty($payables)) {
            Flash::error('Payables not found');

            return redirect(route('payables.index'));
        }

        $payables = $this->payablesRepository->update($request->all(), $id);

        Flash::success('Payables updated successfully.');

        return redirect(route('payables.index'));
    }

    /**
     * Remove the specified Payables from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $payables = $this->payablesRepository->find($id);

        if (empty($payables)) {
            Flash::error('Payables not found');

            return redirect(route('payables.index'));
        }

        $this->payablesRepository->delete($id);

        Flash::success('Payables deleted successfully.');

        return redirect(route('payables.index'));
    }
}
