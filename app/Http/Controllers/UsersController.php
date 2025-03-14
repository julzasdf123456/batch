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
use App\Models\StudentSubjects;
use App\Models\Classes;
use App\Models\ClassesRepo;
use App\Models\SubjectClasses;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
        $data['Adviser'] = Teachers::find($class != null ? $class->Adviser : '0');

        if ($class != null) {
            if ($class->Year == 'Grade 11' | $class->Year == 'Grade 12') {
                $classRepo = DB::table('ClassesRepo')
                    ->where('Year', $class->Year)
                    ->where('Section', $class->Section)
                    ->where('Strand', $class->Strand)
                    ->where('Semester', $class->Semester)
                    ->first();
            } else {
                $classRepo = DB::table('ClassesRepo')
                    ->where('Year', $class->Year)
                    ->where('Section', $class->Section)
                    ->first();
            }

            $data['ClassRepo'] = $classRepo;
        } else {
            $data['ClassRepo'] = null;
        }

        $data['SchoolYear'] = DB::table('SchoolYear')->where('id', $schoolYearId)->first();
        
        if ($class != null) {
            $data['Male'] =  DB::table('StudentClasses')
                ->leftJoin('Students', DB::raw("TRY_CAST(StudentClasses.StudentId AS VARCHAR(100))"), '=', DB::raw("TRY_CAST(Students.id AS VARCHAR(100))"))
                ->leftJoin('Towns', DB::raw("TRY_CAST(Students.Town AS VARCHAR(100))"), '=', DB::raw("TRY_CAST(Towns.id AS VARCHAR(100))"))
                ->leftJoin('Barangays', DB::raw("TRY_CAST(Students.Barangay AS VARCHAR(100))"), '=', DB::raw("TRY_CAST(Barangays.id AS VARCHAR(100))"))
                ->whereRaw("StudentClasses.ClassId='" . $classId . "' AND Gender='Male'")
                ->whereRaw("Students.Status IS NULL")
                ->select(
                    'Students.*',
                    'Towns.Town AS TownSpelled',
                    'Barangays.Barangay AS BarangaySpelled',
                    'StudentClasses.Status as EnrollmentStatus',
                    'StudentClasses.id as StudentClassId'
                )
                ->orderBy('Students.LastName')
                ->get();

            $data['Female'] =  DB::table('StudentClasses')
                ->leftJoin('Students', 'StudentClasses.StudentId', '=', 'Students.id')
                ->leftJoin('Towns', DB::raw("TRY_CAST(Students.Town AS VARCHAR(100))"), '=', DB::raw("TRY_CAST(Towns.id AS VARCHAR(100))"))
                ->leftJoin('Barangays', DB::raw("TRY_CAST(Students.Barangay AS VARCHAR(100))"), '=', DB::raw("TRY_CAST(Barangays.id AS VARCHAR(100))"))
                ->whereRaw("StudentClasses.ClassId='" . $classId . "' AND Gender='Female'")
                ->whereRaw("Students.Status IS NULL")
                ->select(
                    'Students.*',
                    'Towns.Town AS TownSpelled',
                    'Barangays.Barangay AS BarangaySpelled',
                    'StudentClasses.Status as EnrollmentStatus',
                    'StudentClasses.id as StudentClassId'
                )
                ->orderBy('Students.LastName')
                ->get();

                
            $data['Inactive'] =  DB::table('StudentClasses')
                ->leftJoin('Students', 'StudentClasses.StudentId', '=', 'Students.id')
                ->leftJoin('Towns', 'Students.Town', '=', 'Towns.id')
                ->leftJoin('Barangays', 'Students.Barangay', '=', 'Barangays.id')
                ->whereRaw("StudentClasses.ClassId='" . $classId . "'")
                ->whereRaw("Students.Status IS NOT NULL")
                ->select(
                    'Students.*',
                    'Towns.Town AS TownSpelled',
                    'Barangays.Barangay AS BarangaySpelled',
                    'StudentClasses.Status as EnrollmentStatus',
                    'StudentClasses.id as StudentClassId'
                )
                ->orderBy('Students.Status')
                ->orderBy('Students.LastName')
                ->get();
        } else {
            $data['Male'] = [];
            $data['Female'] = [];
            $data['Inactive'] = [];
        }

        return response()->json($data, 200);
    }

    public function getSubjectsFromClass(Request $request) {
        $classId = $request['ClassId'];

        $data = DB::table('StudentSubjects')
            ->leftJoin('Subjects', 'StudentSubjects.SubjectId', '=', 'Subjects.id')
            ->leftJoin('Teachers', 'Subjects.Teacher', '=', 'Teachers.id')
            ->whereRaw("StudentSubjects.ClassId='" . $classId . "'")
            ->select('Subjects.Subject', 'Subjects.id', 'StudentSubjects.TeacherId', 'Teachers.FullName', 'Subjects.ParentSubject', 'StudentSubjects.Heirarchy')
            ->groupBy('Subjects.Subject', 'Subjects.id', 'StudentSubjects.TeacherId', 'Teachers.FullName', 'Subjects.ParentSubject', 'StudentSubjects.Heirarchy')
            ->orderBy('StudentSubjects.Heirarchy')
            ->orderBy('Subjects.ParentSubject')
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

    public function updatePassword(Request $request) {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Assuming you have a user authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated.',
            ], 401);
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password updated successfully.',
        ]);
    }
    
    public function updatePasswordAdmin(Request $request) {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Assuming you have a user authenticated
        $user = Users::find($request->user_id);
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated.',
            ], 401);
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password updated successfully.',
        ]);
    }

    public function removeStudentSubjects(Request $request) {
        $subjectId = $request['SubjectId'];
        $classId = $request['ClassId'];
        $teacherId = $request['TeacherId'];

        $class = Classes::find($classId);

        StudentSubjects::where('SubjectId', $subjectId)
            ->where('ClassId', $classId)
            ->where('TeacherId', $teacherId)
            ->delete();

        // remove also in SubjectClasses
        if ($class != null) {
            if ($class->Year == 'Grade 11' | $class->Year == 'Grade 12') {
                $classRepo = DB::table('ClassesRepo')
                    ->where('Year', $class->Year)
                    ->where('Section', $class->Section)
                    ->where('Strand', $class->Strand)
                    ->where('Semester', $class->Semester)
                    ->first();

                if ($classRepo != null) {
                    SubjectClasses::where('SubjectId', $subjectId)
                        ->where('ClassRepoId', $classRepo->id)
                        ->delete();
                }
            } else {
                $classRepo = DB::table('ClassesRepo')
                    ->where('Year', $class->Year)
                    ->where('Section', $class->Section)
                    ->first();

                if ($classRepo != null) {
                    SubjectClasses::where('SubjectId', $subjectId)
                        ->where('ClassRepoId', $classRepo->id)
                        ->delete();
                }
            }
        }

        return response()->json('ok', 200);
    }

    public function getHomeRoomSubjects(Request $request) {
        $data = DB::table('Subjects')
            ->whereRaw("Subject LIKE '%Homeroom Guidance%'")
            ->get();

        return response()->json($data, 200);
    }
}
