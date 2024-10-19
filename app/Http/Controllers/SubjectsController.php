<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubjectsRequest;
use App\Http\Requests\UpdateSubjectsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\SubjectsRepository;
use Illuminate\Http\Request;
use App\Models\Teachers;
use App\Models\Subjects;
use App\Models\ClassSubjectParentAvg;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;

class SubjectsController extends AppBaseController
{
    /** @var SubjectsRepository $subjectsRepository*/
    private $subjectsRepository;

    public function __construct(SubjectsRepository $subjectsRepo)
    {
        $this->middleware('auth');
        $this->subjectsRepository = $subjectsRepo;
    }

    /**
     * Display a listing of the Subjects.
     */
    public function index(Request $request)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'view subjects'])) {
            $subjects = DB::table('Subjects')
                ->leftJoin('Teachers', 'Subjects.Teacher', '=', 'Teachers.id')
                ->select(
                    'Subjects.*',
                    'Teachers.Fullname'
                )
                ->paginate(100);

            return view('subjects.index')
                ->with('subjects', $subjects);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Show the form for creating a new Subjects.
     */
    public function create()
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'create subjects'])) {
            return view('subjects.create', [
                'teachers' => Teachers::orderBy('FullName')->pluck('FullName', 'id'),
            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Store a newly created Subjects in storage.
     */
    public function store(CreateSubjectsRequest $request)
    {
        $input = $request->all();

        $subjects = $this->subjectsRepository->create($input);

        Flash::success('Subjects saved successfully.');

        return redirect(route('subjects.index'));
    }

    /**
     * Display the specified Subjects.
     */
    public function show($id)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'view subjects'])) {
            $subjects = $this->subjectsRepository->find($id);

            if (empty($subjects)) {
                Flash::error('Subjects not found');

                return redirect(route('subjects.index'));
            }

            return view('subjects.show')->with('subjects', $subjects);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Show the form for editing the specified Subjects.
     */
    public function edit($id)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'edit subjects'])) {
            $subjects = $this->subjectsRepository->find($id);

            if (empty($subjects)) {
                Flash::error('Subjects not found');

                return redirect(route('subjects.index'));
            }

            return view('subjects.edit', [
                'subjects' => $subjects,
                'teachers' => Teachers::orderBy('FullName')->pluck('FullName', 'id'),
            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Update the specified Subjects in storage.
     */
    public function update($id, UpdateSubjectsRequest $request)
    {
        $subjects = $this->subjectsRepository->find($id);

        if (empty($subjects)) {
            Flash::error('Subjects not found');

            return redirect(route('subjects.index'));
        }

        $subjects = $this->subjectsRepository->update($request->all(), $id);

        Flash::success('Subjects updated successfully.');

        return redirect(route('subjects.index'));
    }

    /**
     * Remove the specified Subjects from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'delete subjects'])) {
            $subjects = $this->subjectsRepository->find($id);

            if (empty($subjects)) {
                Flash::error('Subjects not found');

                return redirect(route('subjects.index'));
            }

            $this->subjectsRepository->delete($id);

            Flash::success('Subjects deleted successfully.');

            return redirect(route('subjects.index'));
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    public function getParentSubjects(Request $request) {
        return response()->json(Subjects::parentSubjects(), 200);
    }

    public function getParentAveragingConfig(Request $request) {
        $classId = $request['ClassId'];

        $arr = [];

        $parents = ClassSubjectParentAvg::where('ClassId', $classId)
            ->select('ParentSubject')
            ->get();

        foreach($parents as $item) {
            array_push($arr, $item->ParentSubject);
        }

        return response()->json($arr, 200);
    }
}
