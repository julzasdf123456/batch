<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClassesRepoRequest;
use App\Http\Requests\UpdateClassesRepoRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ClassesRepoRepository;
use Illuminate\Http\Request;
use App\Models\ClassesRepo;
use App\Models\Teachers;
use App\Models\Subjects;
use App\Models\TuitionInclusions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;

class ClassesRepoController extends AppBaseController
{
    /** @var ClassesRepoRepository $classesRepoRepository*/
    private $classesRepoRepository;

    public function __construct(ClassesRepoRepository $classesRepoRepo)
    {
        $this->middleware('auth');
        $this->classesRepoRepository = $classesRepoRepo;
    }

    /**
     * Display a listing of the ClassesRepo.
     */
    public function index(Request $request)
    {
        $classesRepos = DB::table('ClassesRepo')
            ->leftJoin('Teachers', 'ClassesRepo.Adviser', '=', 'Teachers.id')
            ->select('ClassesRepo.*', 'Teachers.FullName')
            ->orderBy('ClassesRepo.Year')
            ->paginate(30);

        return view('classes_repos.index', [
            'classesRepos' => $classesRepos
        ]);
    }

    /**
     * Show the form for creating a new ClassesRepo.
     */
    public function create()
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'create class repos'])) {
            return view('classes_repos.create', [
                'teachers' => Teachers::orderBy('FullName')->pluck('FullName', 'id'),
            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Store a newly created ClassesRepo in storage.
     */
    public function store(CreateClassesRepoRequest $request)
    {
        $input = $request->all();

        $classesRepo = $this->classesRepoRepository->create($input);

        Flash::success('Classes Repo saved successfully.');

        return redirect(route('classesRepos.index'));
    }

    /**
     * Display the specified ClassesRepo.
     */
    public function show($id)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'view class repos'])) {
            $classesRepo = $this->classesRepoRepository->find($id);

            if (empty($classesRepo)) {
                Flash::error('Classes Repo not found');

                return redirect(route('classesRepos.index'));
            }

            $subjectClasses = DB::table('SubjectClasses')
                ->leftJoin('Subjects', 'SubjectClasses.SubjectId', '=', 'Subjects.id')
                ->leftJoin('Teachers', 'Subjects.Teacher', '=', 'Teachers.id')
                ->whereRaw("SubjectClasses.ClassRepoId='" . $id . "'")
                ->select('Subjects.*', 'SubjectClasses.id AS SubjectClassId', 'Teachers.FullName')
                ->get();

            $totalSubjectTuition = DB::table('SubjectClasses')
                ->leftJoin('Subjects', 'SubjectClasses.SubjectId', '=', 'Subjects.id')
                ->whereRaw("SubjectClasses.ClassRepoId='" . $id . "'")
                ->select(
                    DB::raw("SUM(Subjects.CourseFee) AS Total")
                )
                ->first();

            $tuitionInclusions = TuitionInclusions::where('ClassRepoId', $id)
                ->where('FromSchool', 'Private')
                ->orderBy('ItemName')
                ->get();

            $tuitionInclusionsPublic = TuitionInclusions::where('ClassRepoId', $id)
                ->where('FromSchool', 'Public')
                ->orderBy('ItemName')
                ->get();

            $subjects = DB::table('Subjects')
                ->leftJoin('Teachers', 'Subjects.Teacher', '=', 'Teachers.id')
                ->select('Subjects.*', 'Teachers.FullName')
                ->orderBy('Subjects.Subject')
                ->get();

            return view('classes_repos.show', [
                'classRepo' => $classesRepo,
                'subjects' => $subjects,
                'subjectClasses' => $subjectClasses,
                'totalSubjectTuition' => $totalSubjectTuition,
                'tuitionInclusions' => $tuitionInclusions,
                'tuitionInclusionsPublic' => $tuitionInclusionsPublic,
            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Show the form for editing the specified ClassesRepo.
     */
    public function edit($id)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'edit class repos'])) {
            $classesRepo = $this->classesRepoRepository->find($id);

            if (empty($classesRepo)) {
                Flash::error('Classes Repo not found');

                return redirect(route('classesRepos.index'));
            }

            return view('classes_repos.edit', [
                'classesRepo' => $classesRepo,
                'teachers' => Teachers::orderBy('FullName')->pluck('FullName', 'id'),
            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Update the specified ClassesRepo in storage.
     */
    public function update($id, UpdateClassesRepoRequest $request)
    {
        $classesRepo = $this->classesRepoRepository->find($id);

        if (empty($classesRepo)) {
            Flash::error('Classes Repo not found');

            return redirect(route('classesRepos.index'));
        }

        $classesRepo = $this->classesRepoRepository->update($request->all(), $id);

        Flash::success('Classes Repo updated successfully.');

        return redirect(route('classesRepos.index'));
    }

    /**
     * Remove the specified ClassesRepo from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'delete class repos'])) {
            $classesRepo = $this->classesRepoRepository->find($id);

            if (empty($classesRepo)) {
                Flash::error('Classes Repo not found');

                return redirect(route('classesRepos.index'));
            }

            $this->classesRepoRepository->delete($id);

            Flash::success('Classes Repo deleted successfully.');

            return redirect(route('classesRepos.index'));
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    public function getGradeLevels(Request $request) {
        $data =  DB::table('ClassesRepo')
            ->leftJoin('Teachers', 'ClassesRepo.Adviser', '=', 'Teachers.id')
            ->select('ClassesRepo.*', 'Teachers.FullName')
            ->orderBy('ClassesRepo.Year')
            ->get();

        return response()->json($data, 200);
    }

    public function getSubjectsInClass(Request $request) {
        $classesRepoId = $request['ClassRepoId'];

        $subjectClasses = DB::table('SubjectClasses')
            ->leftJoin('Subjects', 'SubjectClasses.SubjectId', '=', 'Subjects.id')
            ->leftJoin('Teachers', 'Subjects.Teacher', '=', 'Teachers.id')
            ->whereRaw("SubjectClasses.ClassRepoId='" . $classesRepoId . "'")
            ->select('Subjects.*', 'SubjectClasses.id AS SubjectClassId', 'Teachers.Fullname', 'Teachers.id AS TeacherId')
            ->get();

        foreach($subjectClasses as $item) {
            $item->Selected = true;
        }

        return response()->json($subjectClasses, 200);
    }

    public function viewClassRepo($year, $section, $strand) {
        $strand = $strand=='xstrand' ? null : $strand;
        $repo = ClassesRepo::where('Year', $year)
            ->where('Section', $section)
            ->where('Strand', $strand)
            ->orderBy('Semester')
            ->first();

        if ($repo != null) {
            return redirect(route('classesRepos.show', [$repo->id]));
        } else {
            return abort(404, "Classes repository not found!");
        }
    }

    public function getAllSubjectRepos(Request $request) {
        $subjects = DB::table('Subjects')
            ->leftJoin('Teachers', 'Subjects.Teacher', '=', 'Teachers.id')
            ->select('Subjects.*', 'Teachers.FullName')
            ->orderBy('Subjects.Subject')
            ->get();

        return response()->json($subjects, 200);
    }
}
