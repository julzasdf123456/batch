<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUsersRequest;
use App\Http\Requests\UpdateUsersRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\UsersRepository;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Users;
use App\Models\Teachers;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Flash;

class UsersController extends AppBaseController
{
    /** @var UsersRepository $usersRepository*/
    private $usersRepository;

    public function __construct(UsersRepository $usersRepo)
    {
        $this->middleware('auth');
        $this->usersRepository = $usersRepo;
    }

    /**
     * Display a listing of the Users.
     */
    public function index(Request $request)
    {
        $users = $this->usersRepository->paginate(10);

        return view('users.index')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new Users.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created Users in storage.
     */
    public function store(CreateUsersRequest $request)
    {
        $input = $request->all();

        $users = $this->usersRepository->create($input);

        Flash::success('Users saved successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Display the specified Users.
     */
    public function show($id)
    {
        $users = $this->usersRepository->find($id);

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        $user = User::find($id);
        $roles = $user->roles;
        $permissions = $user->getAllPermissions();

        return view('users.show', [
            'users' => $users, 
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Show the form for editing the specified Users.
     */
    public function edit($id)
    {
        $users = $this->usersRepository->find($id);

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        return view('users.edit', [
            'users' => $users,
            'teachers' => Teachers::where('Status', 'ACTIVE')->orderBy('FullName')->get()
        ]);
    }

    /**
     * Update the specified Users in storage.
     */
    public function update($id, UpdateUsersRequest $request)
    {
        $users = $this->usersRepository->find($id);

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        $users = $this->usersRepository->update($request->all(), $id);

        Flash::success('Users updated successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified Users from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $users = $this->usersRepository->find($id);

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        $this->usersRepository->delete($id);

        Flash::success('Users deleted successfully.');

        return redirect(route('users.index'));
    }
    
    public function switchColorModes(Request $request) {
        $id = $request['id'];
        $color = $request['Color'];

        $user = User::find($id);

        if ($user != null) {
            $user->ColorProfile = $color;
            $user->save();
        }

        return response()->json($user, 200);
    }

    public function addRoles($id) {
        $users = User::find($id);

        $roles = Role::all();

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        return view('/users/add_roles', ['users' => $users, 'roles' => $roles]);
    }
    
    public function createRoles(Request $request) {
        $user = User::find($request->userId);

        $user->syncPermissions($request->input('permissions', []));

        return redirect('users/' . $request->userId);
    }
    
    public function createUserRoles(Request $request) {
        $user = User::find($request->userId);

        $user->syncRoles($request->input('roles', []));

        // return redirect(route('users.add_user_permissions', ['id' => $user->id, 'roles' => $request->input('roles', [])]));
        return redirect('users/' . $request->userId);
    }

    public function myAccountIndex(Request $request) {
        if (Auth::user()->TeacherId != null) {
            return view('/my_account/index', [

            ]);
        } else {
            return view('/error_messages/not-allowed');
        }
    }

    public function myClasses(Request $request) {
        if (Auth::user()->TeacherId != null) {
            return view('/my_account/my_classes', [

            ]);
        } else {
            return view('/error_messages/not-allowed');
        }
    }

    public function myAdvisory(Request $request) {
        if (Auth::user()->TeacherId != null) {
            return view('/my_account/my_advisory', [

            ]);
        } else {
            return view('/error_messages/not-allowed');
        }
    }

    public function viewClass($classId, $syId, $subjectId) {
        if (Auth::user()->TeacherId != null) {
            return view('/my_account/view_class', [
                'classId' => $classId,
                'syId' => $syId,
                'subjectId' => $subjectId,
            ]);
        } else {
            return view('/error_messages/not-allowed');
        }
    }

    public function getAdvisoryData(Request $request) {
        $id = $request['TeacherId'];

        $schoolYears = DB::table('Classes')
            ->leftJoin('SchoolYear', 'Classes.SchoolYearId', '=', 'SchoolYear.id')
            ->whereRaw("Classes.Adviser='" . $id . "' AND SchoolYear.id IS NOT NULL")
            ->select(
                'SchoolYear.id',
                'SchoolYear.SchoolYear',
            )
            ->groupBy(
                'SchoolYear.id',
                'SchoolYear.SchoolYear',
            )
            ->orderByDesc('SchoolYear.SchoolYear')
            ->get();

        foreach($schoolYears as $item) {
            $item->Advisories = DB::table('Classes')
                ->leftJoin('SchoolYear', 'Classes.SchoolYearId', '=', 'SchoolYear.id')
                ->whereRaw("Classes.Adviser='" . $id . "' AND SchoolYear.id='" . $item->id .  "'")
                ->select('Classes.*')
                ->get();
        }

        return response()->json($schoolYears, 200);
    }

    public function viewAdvisory($adviser, $schoolYearId, $classId) {
        return view('/my_account/view_advisory', [
            'adviser' => $adviser,
            'schoolYearId' => $schoolYearId,
            'classId' => $classId,
        ]);
    }

    public function getAdvisoryDetaills(Request $request) {
        $teacherId = $request['TeacherId'];
        $schoolYearId = $request['SchoolYearId'];
        $classId = $request['ClassId'];

        $data = [];

        $class = DB::table('Classes')
            ->whereRaw("id='" . $classId . "'")
            ->first();

        $data['Class'] = $class;

        $data['SchoolYear'] = DB::table('SchoolYear')->where('id', $schoolYearId)->first();
        
        if ($class != null) {
            $data['Male'] =  DB::table('StudentClasses')
                ->leftJoin('Students', 'StudentClasses.StudentId', '=', 'Students.id')
                ->leftJoin('Towns', 'Students.Town', '=', 'Towns.id')
                ->leftJoin('Barangays', 'Students.Barangay', '=', 'Barangays.id')
                ->whereRaw("StudentClasses.ClassId='" . $classId . "' AND Gender='Male'")
                ->select(
                    'Students.*',
                    'Towns.Town AS TownSpelled',
                    'Barangays.Barangay AS BarangaySpelled',
                )
                ->orderBy('Students.LastName')
                ->get();

            $data['Female'] =  DB::table('StudentClasses')
                ->leftJoin('Students', 'StudentClasses.StudentId', '=', 'Students.id')
                ->leftJoin('Towns', 'Students.Town', '=', 'Towns.id')
                ->leftJoin('Barangays', 'Students.Barangay', '=', 'Barangays.id')
                ->whereRaw("StudentClasses.ClassId='" . $classId . "' AND Gender='Female'")
                ->select(
                    'Students.*',
                    'Towns.Town AS TownSpelled',
                    'Barangays.Barangay AS BarangaySpelled',
                )
                ->orderBy('Students.LastName')
                ->get();
        } else {
            $data['Male'] = [];
            $data['Female'] = [];
        }

        return response()->json($data, 200);
    }

    public function getSubjectsFromClass(Request $request) {
        $classId = $request['ClassId'];

        $data = DB::table('StudentSubjects')
            ->leftJoin('Subjects', 'StudentSubjects.SubjectId', '=', 'Subjects.id')
            ->whereRaw("StudentSubjects.ClassId='" . $classId . "'")
            ->select('Subjects.Subject', 'Subjects.id')
            ->groupBy('Subjects.Subject', 'Subjects.id')
            ->orderBy('Subjects.Subject')
            ->get();

        return response()->json($data, 200);
    }

    public function getStudentSubjectsDataFromClass(Request $request) {
        $classId = $request['ClassId'];

        $data = DB::table('StudentSubjects')
            ->whereRaw("ClassId='" . $classId . "'")
            ->select('*')
            ->get();

        return response()->json($data, 200);
    }
}
