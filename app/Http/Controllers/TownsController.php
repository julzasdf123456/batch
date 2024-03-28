<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTownsRequest;
use App\Http\Requests\UpdateTownsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TownsRepository;
use Illuminate\Http\Request;
use App\Models\Towns;
use Flash;

class TownsController extends AppBaseController
{
    /** @var TownsRepository $townsRepository*/
    private $townsRepository;

    public function __construct(TownsRepository $townsRepo)
    {
        $this->middleware('auth');
        $this->townsRepository = $townsRepo;
    }

    /**
     * Display a listing of the Towns.
     */
    public function index(Request $request)
    {
        $towns = $this->townsRepository->paginate(10);

        return view('towns.index')
            ->with('towns', $towns);
    }

    /**
     * Show the form for creating a new Towns.
     */
    public function create()
    {
        return view('towns.create');
    }

    /**
     * Store a newly created Towns in storage.
     */
    public function store(CreateTownsRequest $request)
    {
        $input = $request->all();

        $towns = $this->townsRepository->create($input);

        Flash::success('Towns saved successfully.');

        return redirect(route('towns.index'));
    }

    /**
     * Display the specified Towns.
     */
    public function show($id)
    {
        $towns = $this->townsRepository->find($id);

        if (empty($towns)) {
            Flash::error('Towns not found');

            return redirect(route('towns.index'));
        }

        return view('towns.show')->with('towns', $towns);
    }

    /**
     * Show the form for editing the specified Towns.
     */
    public function edit($id)
    {
        $towns = $this->townsRepository->find($id);

        if (empty($towns)) {
            Flash::error('Towns not found');

            return redirect(route('towns.index'));
        }

        return view('towns.edit')->with('towns', $towns);
    }

    /**
     * Update the specified Towns in storage.
     */
    public function update($id, UpdateTownsRequest $request)
    {
        $towns = $this->townsRepository->find($id);

        if (empty($towns)) {
            Flash::error('Towns not found');

            return redirect(route('towns.index'));
        }

        $towns = $this->townsRepository->update($request->all(), $id);

        Flash::success('Towns updated successfully.');

        return redirect(route('towns.index'));
    }

    /**
     * Remove the specified Towns from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $towns = $this->townsRepository->find($id);

        if (empty($towns)) {
            Flash::error('Towns not found');

            return redirect(route('towns.index'));
        }

        $this->townsRepository->delete($id);

        Flash::success('Towns deleted successfully.');

        return redirect(route('towns.index'));
    }

    public function getTowns(Request $request) {
        return response()->json(Towns::orderBy('Town')->get(), 200);
    }
}
