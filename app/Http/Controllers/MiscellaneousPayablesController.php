<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMiscellaneousPayablesRequest;
use App\Http\Requests\UpdateMiscellaneousPayablesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\MiscellaneousPayablesRepository;
use Illuminate\Http\Request;
use Flash;

class MiscellaneousPayablesController extends AppBaseController
{
    /** @var MiscellaneousPayablesRepository $miscellaneousPayablesRepository*/
    private $miscellaneousPayablesRepository;

    public function __construct(MiscellaneousPayablesRepository $miscellaneousPayablesRepo)
    {
        $this->middleware('auth');
        $this->miscellaneousPayablesRepository = $miscellaneousPayablesRepo;
    }

    /**
     * Display a listing of the MiscellaneousPayables.
     */
    public function index(Request $request)
    {
        $miscellaneousPayables = $this->miscellaneousPayablesRepository->paginate(10);

        return view('miscellaneous_payables.index')
            ->with('miscellaneousPayables', $miscellaneousPayables);
    }

    /**
     * Show the form for creating a new MiscellaneousPayables.
     */
    public function create()
    {
        return view('miscellaneous_payables.create');
    }

    /**
     * Store a newly created MiscellaneousPayables in storage.
     */
    public function store(CreateMiscellaneousPayablesRequest $request)
    {
        $input = $request->all();

        $miscellaneousPayables = $this->miscellaneousPayablesRepository->create($input);

        Flash::success('Miscellaneous Payables saved successfully.');

        return redirect(route('miscellaneousPayables.index'));
    }

    /**
     * Display the specified MiscellaneousPayables.
     */
    public function show($id)
    {
        $miscellaneousPayables = $this->miscellaneousPayablesRepository->find($id);

        if (empty($miscellaneousPayables)) {
            Flash::error('Miscellaneous Payables not found');

            return redirect(route('miscellaneousPayables.index'));
        }

        return view('miscellaneous_payables.show')->with('miscellaneousPayables', $miscellaneousPayables);
    }

    /**
     * Show the form for editing the specified MiscellaneousPayables.
     */
    public function edit($id)
    {
        $miscellaneousPayables = $this->miscellaneousPayablesRepository->find($id);

        if (empty($miscellaneousPayables)) {
            Flash::error('Miscellaneous Payables not found');

            return redirect(route('miscellaneousPayables.index'));
        }

        return view('miscellaneous_payables.edit')->with('miscellaneousPayables', $miscellaneousPayables);
    }

    /**
     * Update the specified MiscellaneousPayables in storage.
     */
    public function update($id, UpdateMiscellaneousPayablesRequest $request)
    {
        $miscellaneousPayables = $this->miscellaneousPayablesRepository->find($id);

        if (empty($miscellaneousPayables)) {
            Flash::error('Miscellaneous Payables not found');

            return redirect(route('miscellaneousPayables.index'));
        }

        $miscellaneousPayables = $this->miscellaneousPayablesRepository->update($request->all(), $id);

        Flash::success('Miscellaneous Payables updated successfully.');

        return redirect(route('miscellaneousPayables.index'));
    }

    /**
     * Remove the specified MiscellaneousPayables from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $miscellaneousPayables = $this->miscellaneousPayablesRepository->find($id);

        if (empty($miscellaneousPayables)) {
            Flash::error('Miscellaneous Payables not found');

            return redirect(route('miscellaneousPayables.index'));
        }

        $this->miscellaneousPayablesRepository->delete($id);

        Flash::success('Miscellaneous Payables deleted successfully.');

        return redirect(route('miscellaneousPayables.index'));
    }
}
