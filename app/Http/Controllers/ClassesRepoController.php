<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClassesRepoRequest;
use App\Http\Requests\UpdateClassesRepoRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ClassesRepoRepository;
use Illuminate\Http\Request;
use App\Models\ClassesRepo;
use App\Models\Teachers;
use Illuminate\Support\Facades\DB;
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
            ->paginate(10);

        return view('classes_repos.index')
            ->with('classesRepos', $classesRepos);
    }

    /**
     * Show the form for creating a new ClassesRepo.
     */
    public function create()
    {
        return view('classes_repos.create', [
            'teachers' => Teachers::orderBy('FullName')->pluck('FullName', 'id'),
        ]);
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
        $classesRepo = $this->classesRepoRepository->find($id);

        if (empty($classesRepo)) {
            Flash::error('Classes Repo not found');

            return redirect(route('classesRepos.index'));
        }

        return view('classes_repos.show')->with('classesRepo', $classesRepo);
    }

    /**
     * Show the form for editing the specified ClassesRepo.
     */
    public function edit($id)
    {
        $classesRepo = $this->classesRepoRepository->find($id);

        if (empty($classesRepo)) {
            Flash::error('Classes Repo not found');

            return redirect(route('classesRepos.index'));
        }

        return view('classes_repos.edit', [
            'classesRepo' => $classesRepo,
            'teachers' => Teachers::orderBy('FullName')->pluck('FullName', 'id'),
        ]);
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
        $classesRepo = $this->classesRepoRepository->find($id);

        if (empty($classesRepo)) {
            Flash::error('Classes Repo not found');

            return redirect(route('classesRepos.index'));
        }

        $this->classesRepoRepository->delete($id);

        Flash::success('Classes Repo deleted successfully.');

        return redirect(route('classesRepos.index'));
    }

    public function getGradeLevels(Request $request) {
        $data =  DB::table('ClassesRepo')
            ->leftJoin('Teachers', 'ClassesRepo.Adviser', '=', 'Teachers.id')
            ->select('ClassesRepo.*', 'Teachers.FullName')
            ->orderBy('ClassesRepo.Year')
            ->get();

        return response()->json($data, 200);
    }
}
