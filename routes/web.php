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
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\StudentClassesController;
use App\Http\Controllers\StudentSubjectsController;

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
Route::get('/students/get-student-details', [StudentsController::class, 'getStudentDetails'])->name('students.get-student-details');
Route::resource('students', StudentsController::class);

Route::get('/classes/enroll/{studentId}', [ClassesController::class, 'enroll'])->name('classes.enroll');
Route::get('/classes/existing-student', [ClassesController::class, 'existingStudent'])->name('classes.existing-student');
Route::post('/classes/save-enrollment', [ClassesController::class, 'saveEnrollment'])->name('classes.save-enrollment');
Route::get('/classes/get-students-from-class', [ClassesController::class, 'getStudentsFromClass'])->name('classes.get-students-from-class');
Route::resource('classes', ClassesController::class);

Route::resource('studentClasses', StudentClassesController::class);

Route::get('/classes_repos/get-grade-levels', [ClassesRepoController::class, 'getGradeLevels'])->name('classesRepos.get-grade-levels');
Route::get('/classes_repos/get-subjects-in-class', [ClassesRepoController::class, 'getSubjectsInClass'])->name('classesRepos.get-subjects-in-class');
Route::resource('classesRepos', ClassesRepoController::class);

Route::get('/school_years/get-school-years', [SchoolYearController::class, 'getSchoolYears'])->name('schoolYears.get-school-years');
Route::resource('schoolYears', SchoolYearController::class);

Route::get('/teachers/get-teacher-data', [TeachersController::class, 'getTeacherData'])->name('teachers.get-teacher-data');
Route::get('/teachers/get-students-from-subject-class', [TeachersController::class, 'getStudentsFromSubjectClass'])->name('teachers.get-students-from-subject-class');
Route::resource('teachers', TeachersController::class);
Route::resource('payables', App\Http\Controllers\PayablesController::class);

Route::get('/transactions/enrollment', [TransactionsController::class, 'enrollment'])->name('transactions.enrollment');
Route::get('/transactions/get-next-or', [TransactionsController::class, 'getNextOR'])->name('transactions.get-next-or');
Route::get('/transactions/get-enrollment-queue', [TransactionsController::class, 'getEnrollmentQueue'])->name('transactions.get-enrollment-queue');
Route::get('/transactions/get-enrollment-payables', [TransactionsController::class, 'getEnrollmentPayables'])->name('transactions.get-enrollment-payables');
Route::post('/transactions/transact-enrollment', [TransactionsController::class, 'transactEnrollment'])->name('transactions.transact-enrollment');
Route::get('/transactions/print-enrollment/{id}', [TransactionsController::class, 'printEnrollment'])->name('transactions.print-enrollment');
Route::get('/transactions/tuitions/{id}', [TransactionsController::class, 'tuitions'])->name('transactions.tuitions');
Route::get('/transactions/tuitions-search', [TransactionsController::class, 'tuitionsSearch'])->name('transactions.tuitions-search');
Route::get('/transactions/search-student', [TransactionsController::class, 'getSearchStudent'])->name('transactions.search-student');
Route::post('/transactions/transact-tuition', [TransactionsController::class, 'transactTuition'])->name('transactions.transact-tuition');
Route::get('/transactions/print-tuition/{id}', [TransactionsController::class, 'printTuition'])->name('transactions.print-tuition');
Route::get('/transactions/get-transactions-from-payable', [TransactionsController::class, 'getTransactionsFromPayable'])->name('transactions.get-transactions-from-payable');
Route::resource('transactions', TransactionsController::class);

Route::resource('transactionDetails', App\Http\Controllers\TransactionDetailsController::class);
Route::resource('subjects', App\Http\Controllers\SubjectsController::class);
Route::resource('subjectClasses', App\Http\Controllers\SubjectClassesController::class);

Route::post('/student_subjects/update-grade', [StudentSubjectsController::class, 'updateGrade'])->name('studentSubjects.update-grade');
Route::resource('studentSubjects', StudentSubjectsController::class);