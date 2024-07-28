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
use App\Http\Controllers\StudentScholarshipsController;
use App\Http\Controllers\BarcodeAttendanceController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\CatchController;

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

Route::get('/users/add-roles/{id}', [UsersController::class, 'addRoles'])->name('users.add-roles');
Route::post('/users/create-roles', [UsersController::class, 'createRoles'])->name('users.create-roles');
Route::post('/users/create-user-roles', [UsersController::class, 'createUserRoles'])->name('users.create-user-roles');
Route::get('/users/switch-color-modes', [UsersController::class, 'switchColorModes'])->name('users.switch-color-modes');

Route::get('/users/my-account-index/', [UsersController::class, 'myAccountIndex'])->name('users.my-account-index');
Route::get('/users/my-classes/', [UsersController::class, 'myClasses'])->name('users.my-classes');
Route::get('/users/my-advisory/', [UsersController::class, 'myAdvisory'])->name('users.my-advisory');
Route::get('/users/view-class/{classId}/{syId}/{subjectId}', [UsersController::class, 'viewClass'])->name('users.view-class');
Route::get('/users/get-advisory-data', [UsersController::class, 'getAdvisoryData'])->name('users.get-advisory-data');
Route::get('/users/view-advisory/{adviserId}/{schoolyearid}/{classId}', [UsersController::class, 'viewAdvisory'])->name('users.view-advisory');
Route::get('/users/get-advisory-details', [UsersController::class, 'getAdvisoryDetaills'])->name('users.get-advisory-details');
Route::get('/users/get-subjects-from-class', [UsersController::class, 'getSubjectsFromClass'])->name('users.get-subjects-from-class');
Route::get('/users/get-student-subjects-data-from-class', [UsersController::class, 'getStudentSubjectsDataFromClass'])->name('users.get-student-subjects-data-from-class');
Route::resource('users', UsersController::class);

Route::get('/roles/add-permissions/{id}', [RolesController::class, 'addPermissions'])->name('roles.add-permissions');
Route::post('/roles/create-role-permissions', [RolesController::class, 'createRolePermissions'])->name('roles.create-role-permissions');
Route::resource('roles', RolesController::class);
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
Route::get('/students/edit-student/{studentId}', [StudentsController::class, 'editStudent'])->name('students.edit-student');
Route::post('/students/update-student', [StudentsController::class, 'updateStudent'])->name('students.update-student');
Route::get('/students/get-student-class-details', [StudentsController::class, 'getStudentClassDetails'])->name('students.get-student-class-details');
Route::get('/students/guest-view/{studentId}', [StudentsController::class, 'guestView'])->name('students.guest-view');
Route::resource('students', StudentsController::class);

Route::get('/classes/enroll/{studentId}', [ClassesController::class, 'enroll'])->name('classes.enroll');
Route::get('/classes/existing-student', [ClassesController::class, 'existingStudent'])->name('classes.existing-student');
Route::post('/classes/save-enrollment', [ClassesController::class, 'saveEnrollment'])->name('classes.save-enrollment');
Route::get('/classes/get-students-from-class', [ClassesController::class, 'getStudentsFromClass'])->name('classes.get-students-from-class');
Route::get('/classes/get-tuitions-breakdown', [ClassesController::class, 'getTuitionBreakdown'])->name('classes.get-tuitions-breakdown');
Route::get('/classes/view-class/{adviserId}/{schoolyearid}/{classId}', [ClassesController::class, 'viewClass'])->name('classes.view-class');
Route::get('/classes/transfer-to-another-class/{studentId}', [ClassesController::class, 'transferToAnotherClass'])->name('classes.transfer-to-another-class');
Route::post('/classes/save-transfer', [ClassesController::class, 'saveTransfer'])->name('classes.save-transfer');
Route::resource('classes', ClassesController::class);

Route::resource('studentClasses', StudentClassesController::class);

Route::get('/classes_repos/get-grade-levels', [ClassesRepoController::class, 'getGradeLevels'])->name('classesRepos.get-grade-levels');
Route::get('/classes_repos/get-subjects-in-class', [ClassesRepoController::class, 'getSubjectsInClass'])->name('classesRepos.get-subjects-in-class');
Route::resource('classesRepos', ClassesRepoController::class);

Route::get('/school_years/get-school-years', [SchoolYearController::class, 'getSchoolYears'])->name('schoolYears.get-school-years');
Route::get('/school_years/get-school-year', [SchoolYearController::class, 'getSchoolYear'])->name('schoolYears.get-school-year');
Route::resource('schoolYears', SchoolYearController::class);

Route::get('/teachers/get-teacher-data', [TeachersController::class, 'getTeacherData'])->name('teachers.get-teacher-data');
Route::get('/teachers/get-class-details', [TeachersController::class, 'getClassDetails'])->name('teachers.get-class-details');
Route::get('/teachers/get-students-from-subject-class', [TeachersController::class, 'getStudentsFromSubjectClass'])->name('teachers.get-students-from-subject-class');
Route::post('/teachers/update-grade-visibility', [TeachersController::class, 'updateGradeVisibility'])->name('teachers.update-grade-visibility');
Route::get('/teachers/get-class-payment-details', [TeachersController::class, 'getClassPaymentDetails'])->name('teachers.get-class-payment-details');
Route::get('/teachers/print-class-payment-details/{classId}/{schoolYearId}/{subjectId}', [TeachersController::class, 'printClassPaymentDetails'])->name('teachers.print-class-payment-details');
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
Route::get('/transactions/miscellaneous-search', [TransactionsController::class, 'miscellaneousSearch'])->name('transactions.miscellaneous-search');
Route::get('/transactions/miscellaneous/{id}', [TransactionsController::class, 'miscellaneous'])->name('transactions.miscellaneous');
Route::get('/transactions/get-misc-payables', [TransactionsController::class, 'getMiscPayables'])->name('transactions.get-misc-payables');
Route::post('/transactions/transact-miscellaneous', [TransactionsController::class, 'transactMiscellaneous'])->name('transactions.transact-miscellaneous');
Route::get('/transactions/print-miscellaneous/{id}', [TransactionsController::class, 'printMiscellaneous'])->name('transactions.print-miscellaneous');
Route::get('/transactions/get-transaction-history', [TransactionsController::class, 'getTransactionHistory'])->name('transactions.get-transaction-history');
Route::get('/transactions/my-dcr', [TransactionsController::class, 'myDcr'])->name('transactions.my-dcr');
Route::get('/transactions/fetch-payments', [TransactionsController::class, 'fetchPayments'])->name('transactions.fetch-payments');
Route::get('/transactions/fetch-transaction-details', [TransactionsController::class, 'fetchTransactionDetails'])->name('transactions.fetch-transaction-details');
Route::get('/transactions/fetch-all-transaction-details', [TransactionsController::class, 'fetchAllTransactionDetails'])->name('transactions.fetch-all-transaction-details');
Route::get('/transactions/print-my-dcr/{date}', [TransactionsController::class, 'printMyDcr'])->name('transactions.print-my-dcr');
Route::get('/transactions/all-dcr', [TransactionsController::class, 'allDcr'])->name('transactions.all-dcr');
Route::post('/transactions/cancel-transaction', [TransactionsController::class, 'cancelTransaction'])->name('transactions.cancel-transaction');
Route::get('/transactions/get-cashiers', [TransactionsController::class, 'getCashiers'])->name('transactions.get-cashiers');
Route::get('/transactions/fetch-admin-payments', [TransactionsController::class, 'fetchAdminPayments'])->name('transactions.fetch-admin-payments');
Route::get('/transactions/fetch-all-admin-transaction-details', [TransactionsController::class, 'fetchAllAdminTransactionDetails'])->name('transactions.fetch-all-admin-transaction-details');
Route::resource('transactions', TransactionsController::class);

Route::resource('transactionDetails', App\Http\Controllers\TransactionDetailsController::class);
Route::resource('subjects', App\Http\Controllers\SubjectsController::class);
Route::resource('subjectClasses', App\Http\Controllers\SubjectClassesController::class);

Route::post('/student_subjects/update-grade', [StudentSubjectsController::class, 'updateGrade'])->name('studentSubjects.update-grade');
Route::resource('studentSubjects', StudentSubjectsController::class);
Route::resource('tuitions-breakdowns', App\Http\Controllers\TuitionsBreakdownController::class);
Route::resource('miscellaneousPayables', App\Http\Controllers\MiscellaneousPayablesController::class);
Route::resource('tuitionInclusions', App\Http\Controllers\TuitionInclusionsController::class);
Route::resource('payableInclusions', App\Http\Controllers\PayableInclusionsController::class);
Route::resource('scholarships', App\Http\Controllers\ScholarshipsController::class);

Route::get('/student_scholarships/scholarship-wizzard/{id}/{from}', [StudentScholarshipsController::class, 'scholarshipWizzard'])->name('studentScholarships.scholarship-wizzard');
Route::get('/student_scholarships/get-available-sy-payables', [StudentScholarshipsController::class, 'getAvailableSYPayables'])->name('studentScholarships.get-available-sy-payables');
Route::get('/student_scholarships/get-grants', [StudentScholarshipsController::class, 'getGrants'])->name('studentScholarships.get-grants');
Route::post('/student_scholarships/apply-scholarship', [StudentScholarshipsController::class, 'applyScholarship'])->name('studentScholarships.apply-scholarship');
Route::post('/student_scholarships/remove-scholarship', [StudentScholarshipsController::class, 'removeScholarship'])->name('studentScholarships.remove-scholarship');
Route::resource('studentScholarships', StudentScholarshipsController::class);

Route::get('/barcode_attendances/punch-student', [BarcodeAttendanceController::class, 'punchStudent'])->name('barcodeAttendances.punch-student');
Route::get('/barcode_attendances/get-sms-queue', [BarcodeAttendanceController::class, 'getSMSQueue'])->name('barcodeAttendances.get-sms-queue');
Route::get('/barcode_attendances/update-sms', [BarcodeAttendanceController::class, 'updateSMS'])->name('barcodeAttendances.update-sms');
Route::get('/barcode_attendances/get-student-logs', [BarcodeAttendanceController::class, 'getStudentLogs'])->name('barcodeAttendances.get-student-logs');
Route::get('/barcode_attendances/get-barcode-attendance-per-class', [BarcodeAttendanceController::class, 'getBarcodeAttendancePerClass'])->name('barcodeAttendances.get-barcode-attendance-per-class');
Route::get('/barcode_attendances/download-sf2-junior/{classId}/{month}/{year}', [BarcodeAttendanceController::class, 'downloadSF2Junior'])->name('barcodeAttendances.download-sf2-junior');
Route::get('/barcode_attendances/download-sf2-senior/{classId}/{month}/{year}', [BarcodeAttendanceController::class, 'downloadSF2Senior'])->name('barcodeAttendances.download-sf2-senior');
Route::resource('barcodeAttendances', BarcodeAttendanceController::class);

Route::get('/error_messages/not-allowed', [CatchController::class, 'notAllowed'])->name('errorMessages.not-allowed');
Route::get('/error_messages/error-with-back/{title}/{msg}/{errorCode}', [CatchController::class, 'errorWithback'])->name('errorMessages.error-with-back');

Route::resource('sms-messages', App\Http\Controllers\SmsMessagesController::class);