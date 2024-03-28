<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUsersRequest;
use App\Http\Requests\UpdateUsersRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\UsersRepository;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Users;
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

        return view('users.show')->with('users', $users);
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

        return view('users.edit')->with('users', $users);
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
}
