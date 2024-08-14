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
    
    public function getSeniorEnrolleesTrend(Request $request) {
        $syCurrent = SchoolYear::orderByDesc('created_at')->first();
        $syPrevious = SchoolYear::orderByDesc('created_at')->offset(1)->first();

        $data = [];

        // CURRENT
        $labelsQuery = DB::table('ClassesRepo')
            ->whereRaw("Year IN ('Grade 11', 'Grade 12')")
            ->select(
                DB::raw("CONCAT(Year, '-', Strand) AS GradeLevels")
            )
            ->groupBy('Year', 'Section', 'Strand')
            ->orderBy('Year')
            ->orderByRaw("CONCAT(Year, ' - ', Strand)")
            ->get();
        $labels = [];
        foreach ($labelsQuery as $item) {
            array_push($labels, $item->GradeLevels);
        }

        $currentDataFirstSem = null;
        $currentDataSecondSem = null;
        if ($syCurrent != null) {
            // 1st sem
            $currentDataFirstSemQuery = DB::table('ClassesRepo')
                ->whereRaw("Year IN ('Grade 11', 'Grade 12')")
                ->select(
                    DB::raw("(SELECT COUNT(s.id) AS Count FROM Students s LEFT JOIN Classes c ON s.CurrentGradeLevel = c.id WHERE c.SchoolYearId='" . $syCurrent->id . "' AND c.Year=ClassesRepo.Year AND c.Section=ClassesRepo.Section AND c.Strand=ClassesRepo.Strand AND c.Semester='1st') AS Count")
                )
                ->groupBy('Year', 'Section', 'Strand')
                ->orderBy('Year')
                ->orderByRaw("CONCAT(Year, ' - ', Strand)")
                ->get()
                ->toArray();

            $currentDataFirstSem = [];
            foreach($currentDataFirstSemQuery as $item) {
                array_push($currentDataFirstSem, intval($item->Count));
            }

            // 2nd sem
            $currentDataSecondSemQuery = DB::table('ClassesRepo')
                ->whereRaw("Year IN ('Grade 11', 'Grade 12')")
                ->select(
                    DB::raw("(SELECT COUNT(s.id) AS Count FROM Students s LEFT JOIN Classes c ON s.CurrentGradeLevel = c.id WHERE c.SchoolYearId='" . $syCurrent->id . "' AND c.Year=ClassesRepo.Year AND c.Section=ClassesRepo.Section AND c.Strand=ClassesRepo.Strand AND c.Semester='2nd') AS Count")
                )
                ->groupBy('Year', 'Section', 'Strand')
                ->orderBy('Year')
                ->orderByRaw("CONCAT(Year, ' - ', Strand)")
                ->get()
                ->toArray();

            $currentDataSecondSem = [];
            foreach($currentDataSecondSemQuery as $item) {
                array_push($currentDataSecondSem, intval($item->Count));
            }
        }

        $data['Labels'] = $labels;
        $data['Current'] = [
            'SchoolYear' => $syCurrent != null ? $syCurrent->SchoolYear : '-',
            'DataFirstSem' => $currentDataFirstSem,
            'DataSecondSem' => $currentDataSecondSem,
        ];

        // PREVIOUS
        $previousDataFirstSem = null;
        $previousDataSecondSem = null;
        if ($syPrevious != null) {
            // 1st sem
            $previousDataFirstSemQuery = DB::table('ClassesRepo')
                ->whereRaw("Year IN ('Grade 11', 'Grade 12')")
                ->select(
                    'Year',
                    'Section',
                    DB::raw("(SELECT COUNT(s.id) AS Count FROM Students s LEFT JOIN Classes c ON s.CurrentGradeLevel = c.id WHERE c.SchoolYearId='" . $syPrevious->id . "' AND c.Year=ClassesRepo.Year AND c.Section=ClassesRepo.Section AND c.Strand=ClassesRepo.Strand AND c.Semester='1st') AS Count")
                )
                ->groupBy('Year', 'Section', 'Strand')
                ->orderBy('Year')
                ->orderByRaw("CONCAT(Year, ' - ', Strand)")
                ->get()
                ->toArray();

            $previousDataFirstSem = [];
            foreach($previousDataFirstSemQuery as $item) {
                array_push($previousDataFirstSem, intval($item->Count));
            }

            // 2nd sem
            $previousDataSecondSemQuery = DB::table('ClassesRepo')
                ->whereRaw("Year IN ('Grade 11', 'Grade 12')")
                ->select(
                    DB::raw("(SELECT COUNT(s.id) AS Count FROM Students s LEFT JOIN Classes c ON s.CurrentGradeLevel = c.id WHERE c.SchoolYearId='" . $syPrevious->id . "' AND c.Year=ClassesRepo.Year AND c.Section=ClassesRepo.Section AND c.Strand=ClassesRepo.Strand AND c.Semester='2nd') AS Count")
                )
                ->groupBy('Year', 'Section', 'Strand')
                ->orderBy('Year')
                ->orderByRaw("CONCAT(Year, ' - ', Strand)")
                ->get()
                ->toArray();

            $previousDataSecondSem = [];
            foreach($previousDataSecondSemQuery as $item) {
                array_push($previousDataSecondSem, intval($item->Count));
            }
        }

        $data['Previous'] = [
            'SchoolYear' => $syPrevious != null ? $syPrevious->SchoolYear : '-',
            'DataFirstSem' => $previousDataFirstSem,
            'DataSecondSem' => $previousDataSecondSem,
        ];

        return response()->json($data, 200);
    }

    public function getMonthlyCollectionTrend(Request $request) {
        $syId = $request['SchoolYearId'];

        $sy = SchoolYear::find($syId);

        $data = [];
        $months = [];
        $counts = [];
        if ($sy != null) {
            $syStartDate = $sy->MonthStart != null ? $sy->MonthStart : date('Y-m-d');

            for ($i=0; $i<11; $i++) {
                $fromDate = date('Y-m-01', strtotime($syStartDate . ' +' . ($i) . ' months'));
                $toDate = date('Y-m-d', strtotime('last day of ' . $fromDate));

                // set labels (months)
                array_push($months, date('M Y', strtotime($fromDate)));

                // set data
                $transactions = DB::table('Transactions')
                    ->whereRaw("(ORDate BETWEEN '" . $fromDate . "' AND '" . $toDate . "') AND Status IS NULL")
                    ->select(
                        DB::raw("SUM(TotalAmountPaid) AS Total")
                    )
                    ->first();

                array_push($counts, $transactions != null && $transactions->Total != null ? floatval($transactions->Total) : 0);
            }
        }

        $data['Labels'] = $months;
        $data['Data'] = $counts;

        return response()->json($data, 200);
    }

    public function appSettings(Request $request) {
        return view('app_settings');
    }
}
