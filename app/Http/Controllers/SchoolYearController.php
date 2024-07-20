<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSchoolYearRequest;
use App\Http\Requests\UpdateSchoolYearRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\SchoolYearRepository;
use Illuminate\Http\Request;
use App\Models\SchoolYear;
use App\Models\Classes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;

class SchoolYearController extends AppBaseController
{
    /** @var SchoolYearRepository $schoolYearRepository*/
    private $schoolYearRepository;

    public function __construct(SchoolYearRepository $schoolYearRepo)
    {
        $this->middleware('auth');
        $this->schoolYearRepository = $schoolYearRepo;
    }

    /**
     * Display a listing of the SchoolYear.
     */
    public function index(Request $request)
    {
        $schoolYears = SchoolYear::orderByDesc('created_at')->paginate(10);

        return view('school_years.index')
            ->with('schoolYears', $schoolYears);
    }

    /**
     * Show the form for creating a new SchoolYear.
     */
    public function create()
    {
        return view('school_years.create');
    }

    /**
     * Store a newly created SchoolYear in storage.
     */
    public function store(CreateSchoolYearRequest $request)
    {
        $input = $request->all();

        $schoolYear = $this->schoolYearRepository->create($input);

        return response()->json($schoolYear, 200);
    }

    /**
     * Display the specified SchoolYear.
     */
    public function show($id)
    {
        $schoolYear = $this->schoolYearRepository->find($id);

        if (empty($schoolYear)) {
            Flash::error('School Year not found');

            return redirect(route('schoolYears.index'));
        }

        $classes = DB::table('Classes')
            ->leftJoin('Teachers', 'Classes.Adviser', '=', 'Teachers.id')
            ->where('Classes.SchoolYearId', $id)
            ->select(
                'Classes.*',
                'Teachers.FullName',
                'Teachers.Designation',
            )
            ->orderBy('Classes.Year')
            ->get();

        return view('school_years.show', [
            'schoolYear' => $schoolYear,
            'classes' => $classes,
        ]);
    }

    /**
     * Show the form for editing the specified SchoolYear.
     */
    public function edit($id)
    {
        $schoolYear = $this->schoolYearRepository->find($id);

        if (empty($schoolYear)) {
            Flash::error('School Year not found');

            return redirect(route('schoolYears.index'));
        }

        return view('school_years.edit')->with('schoolYear', $schoolYear);
    }

    /**
     * Update the specified SchoolYear in storage.
     */
    public function update($id, UpdateSchoolYearRequest $request)
    {
        $schoolYear = $this->schoolYearRepository->find($id);

        if (empty($schoolYear)) {
            Flash::error('School Year not found');

            return redirect(route('schoolYears.index'));
        }

        $schoolYear = $this->schoolYearRepository->update($request->all(), $id);

        Flash::success('School Year updated successfully.');

        return redirect(route('schoolYears.index'));
    }

    /**
     * Remove the specified SchoolYear from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $schoolYear = $this->schoolYearRepository->find($id);

        if (empty($schoolYear)) {
            Flash::error('School Year not found');

            return redirect(route('schoolYears.index'));
        }

        $this->schoolYearRepository->delete($id);

        Flash::success('School Year deleted successfully.');

        return redirect(route('schoolYears.index'));
    }

    public function getSchoolYears(Request $request) {
        return response()->json(SchoolYear::orderByDesc('created_at')->get(), 200);
    }

    public function getSchoolYear(Request $request) {
        return response()->json(SchoolYear::where('SchoolYear', $request['SchoolYear'])->orderByDesc('created_at')->first(), 200);
    }
}
