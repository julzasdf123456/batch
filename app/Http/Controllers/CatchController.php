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
use Flash;

class CatchController extends AppBaseController
{
    public function notAllowed(Request $request) {
        return view('/error_messages/not_allowed');
    } 
    
    public function errorWithback($title, $message, $errorCode) {
        return view('/error_messages/error_with_back', [
            'title' => $title,
            'errorCode' => $errorCode,
            'message' => $message,
        ]);
    } 
}