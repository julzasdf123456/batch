<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSchoolYearRequest;
use App\Http\Requests\UpdateSchoolYearRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\SchoolYearRepository;
use Illuminate\Http\Request;
use App\Models\SchoolYear;
use App\Models\Classes;
use App\Models\StudentScholarships;
use App\Models\Payables;
use App\Models\ClassSubjectParentAvg;
use App\Models\ObservedValues;
use App\Models\QuizScores;
use App\Models\StudentClasses;
use App\Models\StudentSubjects;
use App\Models\Students;
use App\Models\IDGenerator;
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
        $input['id'] = IDGenerator::generateID();

        $schoolYear = $this->schoolYearRepository->create($input);

        return response()->json($schoolYear, 200);
    }

    /**
     * Display the specified SchoolYear.
     */
    public function show($id)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'view school year']))
        {
            $schoolYear = $this->schoolYearRepository->find($id);

            if (empty($schoolYear))
            {
                Flash::error('School Year not found');

                return redirect(route('schoolYears.index'));
            }

            $sys = SchoolYear::whereRaw("id NOT IN ('" . $id . "')")
                ->orderByDesc('created_at')
                ->get();

            // FETCH THE STUDENTS
            $studentCounts = DB::table('StudentClasses')
                ->select('ClassId', DB::raw('COUNT(*) as StudentsCount'))
                ->groupBy('ClassId');

            // FETCH THE CLASSES WITH STUDENT'S COUNT
            $classes = DB::table('Classes')
                ->leftJoin('Teachers', 'Classes.Adviser', '=', 'Teachers.id')
                ->leftJoinSub($studentCounts, 'sc', function ($join) {
                    $join->on('Classes.id', '=', 'sc.ClassId');
                })
                ->where('Classes.SchoolYearId', $id)
                ->select(
                    'Classes.*',
                    'Teachers.FullName',
                    'Teachers.Designation',
                    DB::raw('ISNULL(sc.StudentsCount, 0) as StudentsCount') // SQL Server
                )
                ->orderBy('Classes.Year')
                ->get();


            return view('school_years.show', [
                'schoolYear' => $schoolYear,
                'classes' => $classes,
                'sys' => $sys,
            ]);
        } else
        {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Show the form for editing the specified SchoolYear.
     */
    public function edit($id)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'edit school year']))
        {
            $schoolYear = $this->schoolYearRepository->find($id);

            if (empty($schoolYear))
            {
                Flash::error('School Year not found');

                return redirect(route('schoolYears.index'));
            }

            return view('school_years.edit')->with('schoolYear', $schoolYear);
        } else
        {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Update the specified SchoolYear in storage.
     */
    public function update($id, UpdateSchoolYearRequest $request)
    {
        $schoolYear = $this->schoolYearRepository->find($id);

        if (empty($schoolYear))
        {
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
        if (Auth::user()->hasAnyPermission(['god permission', 'delete school year']))
        {
            $schoolYear = $this->schoolYearRepository->find($id);

            if (empty($schoolYear))
            {
                Flash::error('School Year not found');

                return redirect(route('schoolYears.index'));
            }

            $this->schoolYearRepository->delete($id);

            Flash::success('School Year deleted successfully.');

            return redirect(route('schoolYears.index'));
        } else
        {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    public function getSchoolYears(Request $request)
    {
        return response()->json(SchoolYear::orderByDesc('created_at')->get(), 200);
    }

    public function getSchoolYear(Request $request)
    {
        return response()->json(SchoolYear::where('SchoolYear', $request['SchoolYear'])->orderByDesc('created_at')->first(), 200);
    }

    public function getClassesInSY(Request $request)
    {
        $syId = $request['SchoolYearId'];

        $classes = DB::table('Classes')
            ->leftJoin('Teachers', 'Classes.Adviser', '=', 'Teachers.id')
            ->where('Classes.SchoolYearId', $syId)
            ->select(
                'Classes.*',
                'Teachers.FullName',
                'Teachers.Designation',
            )
            ->orderBy('Classes.Year')
            ->get();

        return response()->json($classes, 200);
    }

    public function mergeToSy(Request $request)
    {
        $syId = $request['SchoolYearId'];
        $newSyId = $request['NewSchoolYearId'];

        $currentSy = SchoolYear::find($syId);

        if ($currentSy != null)
        {
            $newSy = SchoolYear::find($newSyId);

            if ($newSy != null)
            {
                // Update Classes First
                // loop through all classes on the current SY
                $currentSyClasses = Classes::where('SchoolYearId', $syId)->get();
                foreach ($currentSyClasses as $item)
                {
                    // get corresponding class (classid) on the other SY
                    $otherClass = null;
                    if ($item->Year == 'Grade 11' | $item->Year == 'Grade 12')
                    {
                        $otherClass = Classes::where('Year', $item->Year)
                            ->where('Section', $item->Section)
                            ->where('Strand', $item->Strand)
                            ->where('Semester', $item->Semester)
                            ->where('SchoolYearId', $newSyId)
                            ->first();
                    } else
                    {
                        $otherClass = Classes::where('Year', $item->Year)
                            ->where('Section', $item->Section)
                            ->where('SchoolYearId', $newSyId)
                            ->first();
                    }

                    if ($otherClass != null)
                    {
                        // UPDATE ClassSubjectParentAvg
                        ClassSubjectParentAvg::where('ClassId', $item->id)
                            ->update(['ClassId' => $otherClass->id]);

                        // UPDATE ObservedValues
                        ObservedValues::where('ClassId', $item->id)
                            ->update(['ClassId' => $otherClass->id]);

                        // UPDATE Payables
                        Payables::where('ClassId', $item->id)
                            ->update(['ClassId' => $otherClass->id]);

                        // UPDATE QuizScores
                        QuizScores::where('ClassId', $item->id)
                            ->update(['ClassId' => $otherClass->id]);

                        // UPDATE StudentClasses
                        StudentClasses::where('ClassId', $item->id)
                            ->update(['ClassId' => $otherClass->id]);

                        // UPDATE StudentSubjects
                        StudentSubjects::where('ClassId', $item->id)
                            ->update(['ClassId' => $otherClass->id]);

                        // UPDATE Students
                        Students::where('CurrentGradeLevel', $item->id)
                            ->update(['CurrentGradeLevel' => $otherClass->id]);
                    }
                }

                // DELETE Classes with SY ID
                Classes::where('SchoolYearId', $syId)
                    ->delete();

                // Update Student Scholarships
                StudentScholarships::where('SchoolYear', $currentSy->SchoolYear)
                    ->update(['SchoolYear' => $newSy->SchoolYear]);

                // Update Student Payables
                Payables::where('SchoolYear', $currentSy->SchoolYear)
                    ->update(['SchoolYear' => $newSy->SchoolYear]);

                // DELETE OLD SY
                $currentSy->delete();

                return response()->json('School years merged!', 200);
            } else
            {
                return response()->json('Selected school year not found!', 404);
            }
        } else
        {
            return response()->json('School year not found!', 404);
        }
    }
    public function viewSummary(Request $request)
    {
        //GET THE SCHOOL YEAR BY ID
        $schoolYearId = $request->school_year_id;

        //GET ALL SUMMARIES WITH THE FETCHED SCHOOL YEAR
        $summaries = StudentClasses::select('Classes.Year', DB::raw('COUNT(StudentClasses.id) as Students'))
            ->join('Classes', 'StudentClasses.ClassId', '=', 'Classes.id')
            ->where('Classes.SchoolYearId', $schoolYearId)
            ->groupBy('Classes.Year')
            ->orderByRaw("CASE Classes.Year
                  WHEN 'Grade 7' THEN 1
                  WHEN 'Grade 8' THEN 2
                  WHEN 'Grade 9' THEN 3
                  WHEN 'Grade 10' THEN 4
                  WHEN 'Grade 11' THEN 5
                  WHEN 'Grade 12' THEN 6
                  ELSE 7 END")
            ->get();

        //RETURN THE SCHOOL YEAR
        $schoolYear = SchoolYear::find($schoolYearId);

        //DISPLAY THE SUMMARIES
        return view('school_years.show_summary', [
            'summaries' => $summaries,
            'schoolYear' => $schoolYear,
        ]);

    }

}
