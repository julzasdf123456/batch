<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SchoolYear;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasPermissionTo('admin ui')) {
            return view('home');
        } else {
            if (Auth::user()->TeacherId != null) {
                return redirect(route('users.my-account-index'));
            } else {
                return redirect(route('errorMessages.not-allowed'));
            }
        }
    }

    public function getJuniorEnrolleesTrend(Request $request) {
        $syCurrent = SchoolYear::orderByDesc('created_at')->first();
        $syPrevious = SchoolYear::orderByDesc('created_at')->offset(1)->first();

        $data = [];

        // CURRENT
        $labelsQuery = DB::table('ClassesRepo')
            ->whereRaw("Year NOT IN ('Grade 11', 'Grade 12')")
            ->select(
                DB::raw("CONCAT(Year, '-', Section) AS GradeLevels")
            )
            ->orderBy('Year')
            ->orderByRaw("CONCAT(Year, ' - ', Section)")
            ->get();
        $labels = [];
        foreach ($labelsQuery as $item) {
            array_push($labels, $item->GradeLevels);
        }

        $currentData = null;
        if ($syCurrent != null) {
            $currentDataQuery = DB::table('ClassesRepo')
                ->whereRaw("Year NOT IN ('Grade 11', 'Grade 12')")
                ->select(
                    'Year',
                    'Section',
                    DB::raw("(SELECT COUNT(s.id) AS Count FROM Students s LEFT JOIN Classes c ON s.CurrentGradeLevel = c.id WHERE c.SchoolYearId='" . $syCurrent->id . "' AND c.Year=ClassesRepo.Year AND c.Section=ClassesRepo.Section) AS Count")
                )
                ->orderBy('Year')
                ->orderByRaw("CONCAT(Year, ' - ', Section)")
                ->get()
                ->toArray();

            $currentData = [];
            foreach($currentDataQuery as $item) {
                array_push($currentData, intval($item->Count));
            }
        }

        $data['Labels'] = $labels;
        $data['Current'] = [
            'SchoolYear' => $syCurrent != null ? $syCurrent->SchoolYear : '-',
            'Data' => $currentData,
        ];

        // PREVIOUS
        $previousData = null;
        if ($syPrevious != null) {
            $previousDataQuery = DB::table('ClassesRepo')
                ->whereRaw("Year NOT IN ('Grade 11', 'Grade 12')")
                ->select(
                    'Year',
                    'Section',
                    DB::raw("(SELECT COUNT(s.id) AS Count FROM Students s LEFT JOIN Classes c ON s.CurrentGradeLevel = c.id WHERE c.SchoolYearId='" . $syPrevious->id . "' AND c.Year=ClassesRepo.Year AND c.Section=ClassesRepo.Section) AS Count")
                )
                ->orderBy('Year')
                ->orderByRaw("CONCAT(Year, ' - ', Section)")
                ->get()
                ->toArray();

            $previousData = [];
            foreach($previousDataQuery as $item) {
                array_push($previousData, intval($item->Count));
            }
        }

        $data['Previous'] = [
            'SchoolYear' => $syPrevious != null ? $syPrevious->SchoolYear : '-',
            'Data' => $previousData,
        ];

        return response()->json($data, 200);
    }
}
