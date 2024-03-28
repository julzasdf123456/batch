<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\TownsController;
use App\Http\Controllers\BarangaysController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('home');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/users/switch-color-modes', [UsersController::class, 'switchColorModes'])->name('users.switch-color-modes');
Route::resource('users', UsersController::class);
Route::resource('roles', App\Http\Controllers\RolesController::class);
Route::resource('permissions', App\Http\Controllers\PermissionsController::class);

Route::get('/towns/get-towns', [TownsController::class, 'getTowns'])->name('towns.get-towns');
Route::resource('towns', TownsController::class);

Route::get('/barangays/get-barangays', [BarangaysController::class, 'getBarangays'])->name('barangays.get-barangays');
Route::resource('barangays', BarangaysController::class);

Route::get('/students/new-student', [StudentsController::class, 'newStudent'])->name('students.new-student');
Route::post('/students/save-student', [StudentsController::class, 'saveStudent'])->name('students.save-student');
Route::resource('students', StudentsController::class);
Route::resource('classes', App\Http\Controllers\ClassesController::class);
Route::resource('studentClasses', App\Http\Controllers\StudentClassesController::class);
Route::resource('classesRepos', App\Http\Controllers\ClassesRepoController::class);