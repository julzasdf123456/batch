<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBarangaysRequest;
use App\Http\Requests\UpdateBarangaysRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BarangaysRepository;
use Illuminate\Http\Request;
use App\Models\Barangays;
use Flash;

class BarangaysController extends AppBaseController
{
    /** @var BarangaysRepository $barangaysRepository*/
    private $barangaysRepository;

    public function __construct(BarangaysRepository $barangaysRepo)
    {
        $this->middleware('auth');
        $this->barangaysRepository = $barangaysRepo;
    }

    /**
     * Display a listing of the Barangays.
     */
    public function index(Request $request)
    {
        $barangays = $this->barangaysRepository->paginate(10);

        return view('barangays.index')
            ->with('barangays', $barangays);
    }

    /**
     * Show the form for creating a new Barangays.
     */
    public function create()
    {
        return view('barangays.create');
    }

    /**
     * Store a newly created Barangays in storage.
     */
    public function store(CreateBarangaysRequest $request)
    {
        $input = $request->all();

        $barangays = $this->barangaysRepository->create($input);

        Flash::success('Barangays saved successfully.');

        return redirect(route('barangays.index'));
    }

    /**
     * Display the specified Barangays.
     */
    public function show($id)
    {
        $barangays = $this->barangaysRepository->find($id);

        if (empty($barangays)) {
            Flash::error('Barangays not found');

            return redirect(route('barangays.index'));
        }

        return view('barangays.show')->with('barangays', $barangays);
    }

    /**
     * Show the form for editing the specified Barangays.
     */
    public function edit($id)
    {
        $barangays = $this->barangaysRepository->find($id);

        if (empty($barangays)) {
            Flash::error('Barangays not found');

            return redirect(route('barangays.index'));
        }

        return view('barangays.edit')->with('barangays', $barangays);
    }

    /**
     * Update the specified Barangays in storage.
     */
    public function update($id, UpdateBarangaysRequest $request)
    {
        $barangays = $this->barangaysRepository->find($id);

        if (empty($barangays)) {
            Flash::error('Barangays not found');

            return redirect(route('barangays.index'));
        }

        $barangays = $this->barangaysRepository->update($request->all(), $id);

        Flash::success('Barangays updated successfully.');

        return redirect(route('barangays.index'));
    }

    /**
     * Remove the specified Barangays from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $barangays = $this->barangaysRepository->find($id);

        if (empty($barangays)) {
            Flash::error('Barangays not found');

            return redirect(route('barangays.index'));
        }

        $this->barangaysRepository->delete($id);

        Flash::success('Barangays deleted successfully.');

        return redirect(route('barangays.index'));
    }

    public function getBarangays(Request $request) {
        $townId = $request['TownId'];

        return response()->json(Barangays::where('TownId', $townId)->orderBy('Barangay')->get(), 200);
    }
}
