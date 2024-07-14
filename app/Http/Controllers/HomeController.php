<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
