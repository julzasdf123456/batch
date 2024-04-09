<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\TownsController;
use App\Http\Controllers\BarangaysController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ClassesRepoController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\TransactionsController;

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
Route::get('/students/get-student', [StudentsController::class, 'getStudent'])->name('students.get-student');
Route::get('/students/search-students-paginated', [StudentsController::class, 'searchStudentsPaginated'])->name('students.search-students-paginated');
Route::resource('students', StudentsController::class);

Route::get('/classes/enroll/{studentId}', [ClassesController::class, 'enroll'])->name('classes.enroll');
Route::get('/classes/existing-student', [ClassesController::class, 'existingStudent'])->name('classes.existing-student');
Route::post('/classes/save-enrollment', [ClassesController::class, 'saveEnrollment'])->name('classes.save-enrollment');
Route::resource('classes', ClassesController::class);

Route::resource('studentClasses', App\Http\Controllers\StudentClassesController::class);

Route::get('/classes_repos/get-grade-levels', [ClassesRepoController::class, 'getGradeLevels'])->name('classesRepos.get-grade-levels');
Route::resource('classesRepos', ClassesRepoController::class);

Route::get('/school_years/get-school-years', [SchoolYearController::class, 'getSchoolYears'])->name('schoolYears.get-school-years');
Route::resource('schoolYears', SchoolYearController::class);
Route::resource('teachers', App\Http\Controllers\TeachersController::class);
Route::resource('payables', App\Http\Controllers\PayablesController::class);

Route::get('/transactions/enrollment', [TransactionsController::class, 'enrollment'])->name('transactions.enrollment');
Route::get('/transactions/get-next-or', [TransactionsController::class, 'getNextOR'])->name('transactions.get-next-or');
Route::get('/transactions/get-enrollment-queue', [TransactionsController::class, 'getEnrollmentQueue'])->name('transactions.get-enrollment-queue');
Route::get('/transactions/get-enrollment-payables', [TransactionsController::class, 'getEnrollmentPayables'])->name('transactions.get-enrollment-payables');
Route::post('/transactions/transact-enrollment', [TransactionsController::class, 'transactEnrollment'])->name('transactions.transact-enrollment');
Route::get('/transactions/print-enrollment/{id}', [TransactionsController::class, 'printEnrollment'])->name('transactions.print-enrollment');
Route::resource('transactions', TransactionsController::class);

Route::resource('transaction-details', App\Http\Controllers\TransactionDetailsController::class);
Route::resource('subjects', App\Http\Controllers\SubjectsController::class);
Route::resource('subjectClasses', App\Http\Controllers\SubjectClassesController::class);