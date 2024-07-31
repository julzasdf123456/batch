<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransactionsRequest;
use App\Http\Requests\UpdateTransactionsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TransactionsRepository;
use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\Payables;
use App\Models\IDGenerator;
use App\Models\TransactionDetails;
use App\Models\StudentClasses;
use App\Models\Students;
use App\Models\ClassesRepo;
use App\Models\Classes;
use App\Models\SchoolYear;
use App\Models\TuitionsBreakdown;
use App\Models\MiscellaneousPayables;
use App\Models\PayableInclusions;
use App\Models\TuitionInclusions;
use App\Models\SmsMessages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;

class TransactionsController extends AppBaseController
{
    /** @var TransactionsRepository $transactionsRepository*/
    private $transactionsRepository;

    public function __construct(TransactionsRepository $transactionsRepo)
    {
        $this->middleware('auth');
        $this->transactionsRepository = $transactionsRepo;
    }

    /**
     * Display a listing of the Transactions.
     */
    public function index(Request $request)
    {
        $transactions = $this->transactionsRepository->paginate(10);

        return view('transactions.index')
            ->with('transactions', $transactions);
    }

    /**
     * Show the form for creating a new Transactions.
     */
    public function create()
    {
        return view('transactions.create');
    }

    /**
     * Store a newly created Transactions in storage.
     */
    public function store(CreateTransactionsRequest $request)
    {
        $input = $request->all();

        $transactions = $this->transactionsRepository->create($input);

        Flash::success('Transactions saved successfully.');

        return redirect(route('transactions.index'));
    }

    /**
     * Display the specified Transactions.
     */
    public function show($id)
    {
        $transactions = $this->transactionsRepository->find($id);

        if (empty($transactions)) {
            Flash::error('Transactions not found');

            return redirect(route('transactions.index'));
        }

        return view('transactions.show')->with('transactions', $transactions);
    }

    /**
     * Show the form for editing the specified Transactions.
     */
    public function edit($id)
    {
        $transactions = $this->transactionsRepository->find($id);

        if (empty($transactions)) {
            Flash::error('Transactions not found');

            return redirect(route('transactions.index'));
        }

        return view('transactions.edit')->with('transactions', $transactions);
    }

    /**
     * Update the specified Transactions in storage.
     */
    public function update($id, UpdateTransactionsRequest $request)
    {
        $transactions = $this->transactionsRepository->find($id);

        if (empty($transactions)) {
            Flash::error('Transactions not found');

            return redirect(route('transactions.index'));
        }

        $transactions = $this->transactionsRepository->update($request->all(), $id);

        Flash::success('Transactions updated successfully.');

        return redirect(route('transactions.index'));
    }

    /**
     * Remove the specified Transactions from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $transactions = $this->transactionsRepository->find($id);

        if (empty($transactions)) {
            Flash::error('Transactions not found');

            return redirect(route('transactions.index'));
        }

        $this->transactionsRepository->delete($id);

        Flash::success('Transactions deleted successfully.');

        return redirect(route('transactions.index'));
    }

    public function enrollment(Request $request) {
        if (Auth::user()->hasAnyPermission(['god permission', 'transact enrollment'])) {
            return view('/transactions/enrollment', [

            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    public function getNextOR(Request $request) {
        $userId = $request['UserId'];
        
        $transactions = Transactions::whereRaw("UserId IS NOT NULL AND UserId='" . $userId . "'")
            ->orderByDesc('ORNumber')
            ->first();

        if ($transactions != null) {
            $orNumber = intval($transactions->ORNumber) + 1;

            return response()->json($orNumber, 200);
        } else {
            return response()->json(0, 200);
        }
    }

    public function getEnrollmentQueue(Request $request) {
        $params = $request['Search'];

        if (isset($params)) {
            $data = DB::table('StudentClasses')
                ->leftJoin('Students', 'StudentClasses.StudentId', '=', 'Students.id')
                ->whereRaw("StudentClasses.Status='Pending Enrollment Payment' AND (Students.FirstName LIKE '%" . $params . "%' OR Students.LastName LIKE '%" . $params . "%' OR Students.MiddleName LIKE '%" . $params . "%' OR 
                    (Students.FirstName + ' ' + Students.LastName) LIKE '%" . $params . "%' OR (Students.LastName + ', ' + Students.FirstName) LIKE '%" . $params . "%' OR 
                    (Students.FirstName + ' ' + Students.MiddleName + ' ' + Students.LastName) LIKE '%" . $params . "%' OR Students.id LIKE '%" . $params . "%')")
                ->select('Students.*', 'StudentClasses.ClassId')
                ->orderBy('Students.FirstName')
                ->paginate(18);
        } else {
            $data = DB::table('StudentClasses')
                ->leftJoin('Students', 'StudentClasses.StudentId', '=', 'Students.id')
                ->whereRaw("StudentClasses.Status='Pending Enrollment Payment'")
                ->select('Students.*', 'StudentClasses.ClassId')
                ->orderByDesc('StudentClasses.created_at')
                ->paginate(18);
        }

        return response()->json($data, 200);
    }

    public function getEnrollmentPayables(Request $request) {
        $studentId = $request['StudentId'];

        $payables = Payables::where('StudentId', $studentId)
            ->whereRaw("Balance > 0 AND Category='Enrollment'")
            ->orderBy('created_at')
            ->get();

        return response()->json($payables, 200);
    }

    public function transactEnrollment(Request $request) {
        $studentId = $request['StudentId'];
        $classId = $request['ClassId'];
        $cashAmount = $request['cashAmount'];
        $checkNumber = $request['checkNumber'];
        $checkBank = $request['checkBank'];
        $checkAmount = $request['checkAmount'];
        $digitalNumber = $request['digitalNumber'];
        $digitalBank = $request['digitalBank'];
        $digitalAmount = $request['digitalAmount'];
        $totalPayables = $request['totalPayables'];
        $totalPayments = $request['totalPayments'];
        $payables = $request['Payables'];
        $orNumber = $request['ORNumber'];

        // update payables
        $payableIds = '';
        foreach($payables as $item) {
            $payableIds .= $item['id'];
            Payables::where('id', $item['id'])
                ->update(['AmountPaid' => $item['AmountPayable'], 'Balance' => 0]);
        }

        $modeOfPayment = '';
        if ($cashAmount != null) {
            $modeOfPayment .= 'Cash;';
        }
        if ($checkAmount != null) {
            $modeOfPayment .= 'Check;';
        }
        if ($digitalAmount != null) {
            $modeOfPayment .= 'Digital;';
        }

        // insert transactions
        $id = IDGenerator::generateIDandRandString();
        $transactions = new Transactions;
        $transactions->id = $id;
        $transactions->PayablesId = $payableIds;
        $transactions->StudentId = $studentId;
        $transactions->PaymentFor = 'Enrollment Fees';
        $transactions->ModeOfPayment = $modeOfPayment;
        $transactions->ORNumber = $orNumber;
        $transactions->ORDate = date('Y-m-d');
        $transactions->CashAmount = $cashAmount;
        $transactions->CheckAmount = $checkAmount;
        $transactions->DigitalPaymentAmount = $digitalAmount;
        $transactions->TotalAmountPaid = $totalPayables;
        $transactions->UserId = Auth::id();
        $transactions->TransactionType = 'Enrollment';
        $transactions->save();

        // insert transaction details
        foreach($payables as $item) {
            $details = new TransactionDetails;
            $details->id = IDGenerator::generateIDandRandString();
            $details->TransactionsId = $id;
            $details->Particulars = $item['PaymentFor'];
            $details->Amount = $item['Balance'];
            $details->save();
        }

        // update student classes
        StudentClasses::where('ClassId', $classId)
            ->where('StudentId', $studentId)
            ->update(['Status' => 'Paid', 'EnrollmentORNumber' => $orNumber, 'EnrollmentORDate' => date('Y-m-d')]);


        // send sms
        $student = Students::find($studentId);
        if ($student != null) {
            SmsMessages::createSmsWithStudentProvided($student, 
                "Holy Cross Academy System Notification\n\nENROLLMENT FEE has been paid for " . $student->FirstName . " " . $student->LastName . " amounting to " . number_format($totalPayables, 2) . ", with transaction number " . $orNumber . ", at " . date('M d, Y h:i A') . ".", 
                2);
        } 

        return response()->json($id, 200);
    }

    public function printEnrollment($transactionId) {
        $transaction = Transactions::find($transactionId);

        if ($transaction != null) {
            $student = DB::table('Students')
                ->leftJoin('Towns', 'Students.Town', '=', 'Towns.id')
                ->leftJoin('Barangays', 'Students.Barangay', '=', 'Barangays.id')
                ->whereRaw("Students.id='" . $transaction->StudentId . "'")
                ->select('Students.*',
                    'Towns.Town as TownSpelled',
                    'Barangays.Barangay as BarangaySpelled')
                ->first();

            $classes = Classes::find($student->CurrentGradeLevel);

            $transactionDetails = TransactionDetails::where('TransactionsId', $transaction->id)->get();

            return view('/transactions/print_enrollment', [
                'transaction' => $transaction,
                'student' => $student,
                'transactionDetails' => $transactionDetails,
                'classes' => $classes,
            ]);
        } else {
            return abort('No transaction found!', 404);
        }
    }

    public function tuitions($studentId) {
        if (Auth::user()->hasAnyPermission(['god permission', 'transact tuitions'])) {
            return view('/transactions/tuitions', [
                'studentId' => $studentId,
            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    public function tuitionsSearch(Request $request) {
        if (Auth::user()->hasAnyPermission(['god permission', 'transact tuitions'])) {
            return view('/transactions/tuitions_search');
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }
    
    public function getSearchStudent(Request $request) {
        $params = $request['Search'];

        if (isset($params)) {
            $data = DB::table('Students')
                ->leftJoin('Towns', 'Students.Town', '=', 'Towns.id')
                ->leftJoin('Barangays', 'Students.Barangay', '=', 'Barangays.id')
                ->leftJoin('Classes', 'Students.CurrentGradeLevel', '=', 'Classes.id')
                ->whereRaw("(Students.FirstName LIKE '%" . $params . "%' OR Students.LastName LIKE '%" . $params . "%' OR Students.MiddleName LIKE '%" . $params . "%' OR 
                    (Students.FirstName + ' ' + Students.LastName) LIKE '%" . $params . "%' OR (Students.LastName + ', ' + Students.FirstName) LIKE '%" . $params . "%' OR 
                    (Students.FirstName + ' ' + Students.MiddleName + ' ' + Students.LastName) LIKE '%" . $params . "%' OR Students.id LIKE '%" . $params . "%')")
                ->select('Students.*',
                    'Towns.Town AS TownSpelled',
                    'Barangays.Barangay AS BarangaySpelled',
                    'Classes.Year',
                    'Classes.Section',
                )
                ->orderBy('Students.LastName')
                ->paginate(13);
        } else {
            $data = DB::table('Students')
                ->leftJoin('Towns', 'Students.Town', '=', 'Towns.id')
                ->leftJoin('Barangays', 'Students.Barangay', '=', 'Barangays.id')
                ->leftJoin('Classes', 'Students.CurrentGradeLevel', '=', 'Classes.id')
                ->select('Students.*',
                    'Towns.Town AS TownSpelled',
                    'Barangays.Barangay AS BarangaySpelled',
                    'Classes.Year',
                    'Classes.Section',
                )
                ->orderBy('Students.LastName')
                ->paginate(13);
        }

        return response()->json($data, 200);
    }

    public function transactTuition(Request $request) {
        $payableId = $request['PayableId'];
        $studentId = $request['StudentId'];
        $cashAmount = $request['cashAmount'];
        $checkNumber = $request['checkNumber'];
        $checkBank = $request['checkBank'];
        $checkAmount = $request['checkAmount'];
        $digitalNumber = $request['digitalNumber'];
        $digitalBank = $request['digitalBank'];
        $digitalAmount = $request['digitalAmount'];
        $totalPayables = $request['totalPayables'];
        $totalPayments = $request['totalPayments'];
        $orNumber = $request['ORNumber'];
        $details = $request['Details'];
        $period = $request['Period'];
        $paidAmount = $request['PaidAmount'];
        $balance = $request['Balance'];
        $tuitionBreakdown = $request['TuitionBreakdowns'];

        // update tuition payable
        $payable = Payables::find($payableId);
        if ($payable != null) {
            $payableAmntPaid = $payable->AmountPaid != null ? floatval($payable->AmountPaid) : 0;
            $newAmntPaid = $payableAmntPaid + floatval($paidAmount);

            $payable->AmountPaid = $newAmntPaid;
            $payable->Balance = $balance;
            $payable->save();
        }

        // determine mode of payment
        $modeOfPayment = '';
        if ($cashAmount != null) {
            $modeOfPayment .= 'Cash;';
        }
        if ($checkAmount != null) {
            $modeOfPayment .= 'Check;';
        }
        if ($digitalAmount != null) {
            $modeOfPayment .= 'Digital;';
        }

        // insert transaction
        $id = IDGenerator::generateIDandRandString();
        $transactions = new Transactions;
        $transactions->id = $id;
        $transactions->PayablesId = $payableId;
        $transactions->StudentId = $studentId;
        $transactions->PaymentFor = $details;
        $transactions->ModeOfPayment = $modeOfPayment;
        $transactions->ORNumber = $orNumber;
        $transactions->ORDate = date('Y-m-d');
        $transactions->CashAmount = $cashAmount;
        $transactions->CheckAmount = $checkAmount;
        $transactions->DigitalPaymentAmount = $digitalAmount;
        $transactions->TotalAmountPaid = $paidAmount;
        $transactions->UserId = Auth::id();
        $transactions->Period = $period;
        $transactions->TransactionType = 'Tuition Fees';
        $transactions->save();

        // insert transaction details
        $transactionDetails = new TransactionDetails;
        $transactionDetails->id = IDGenerator::generateIDandRandString();
        $transactionDetails->TransactionsId = $id;
        $transactionDetails->Particulars = $details;
        $transactionDetails->Amount = $paidAmount;
        $transactionDetails->save();

        // update tuitions breakdown
        $tBreakdown = TuitionsBreakdown::where('PayableId', $payableId)->whereRaw("Balance > 0")->orderBy('ForMonth')->get();
        $payment = $paidAmount;
        foreach($tBreakdown as $item) {
            $currentPayable = floatval($item->Balance);
            if ($payment > 0) {
                if ($payment >= $currentPayable) {
                    $item->Balance = 0;
                    $item->AmountPaid = $item->AmountPayable;
                    
                    $payment = $payment - $currentPayable;
                } else {
                    $item->Balance = $currentPayable - $payment;
                    $item->AmountPaid = floatval($item->AmountPaid) + $payment;

                    $payment = 0;
                }

                $item->TransactionId = $id;
                $item->save();
            }
        }

        // send sms
        $student = Students::find($studentId);
        if ($student != null) {
            SmsMessages::createSmsWithStudentProvided($student, 
                "Holy Cross Academy System Notification\n\nTUITION FEE has been paid for " . $student->FirstName . " " . $student->LastName . " amounting to " . number_format($paidAmount, 2) . ", with transaction number " . $orNumber . ", at " . date('M d, Y h:i A') . ".", 
                2);
        }        

        return response()->json($id, 200);
    }

    public function printTuition($transactionId) {
        $transaction = Transactions::find($transactionId);
        
        if ($transaction != null) {
            $transactionDetails = TransactionDetails::where('TransactionsId', $transactionId)->get();
            $student = DB::table('Students')
                    ->leftJoin('Towns', 'Students.Town', '=', 'Towns.id')
                    ->leftJoin('Barangays', 'Students.Barangay', '=', 'Barangays.id')
                    ->whereRaw("Students.id='" . $transaction->StudentId . "'")
                    ->select('Students.*',
                        'Towns.Town as TownSpelled',
                        'Barangays.Barangay as BarangaySpelled')
                    ->first();

            $classes = Classes::find($student->CurrentGradeLevel);

            return view('/transactions/print_tuition', [
                'transaction' => $transaction,
                'student' => $student,
                'transactionDetails' => $transactionDetails,
                'classes' => $classes,
            ]);
        } else {
            return abort('No transaction found!', 404);
        }
    }

    public function getTransactionsFromPayable(Request $request) {
        $payableId = $request['PayableId'];

        $data = [];

        $data['Transactions'] = DB::table('Transactions')
            ->leftJoin('users', 'Transactions.UserId', '=', 'users.id')
            ->where('PayablesId', $payableId)
            ->select('Transactions.*', 'users.name')
            ->orderByDesc('created_at')
            ->get();

        $data['TuitionLogs'] = DB::table('TuitionsBreakdown')
            ->where('PayableId', $payableId)
            ->orderBy('ForMonth')
            ->get();

        $data['PayableInclusions'] = DB::table('PayableInclusions')
            ->where('PayableId', $payableId)
            ->orderBy('ItemName')
            ->get();

        return response()->json($data, 200);
    }

    public function miscellaneousSearch(Request $request) {
        if (Auth::user()->hasAnyPermission(['god permission', 'transact miscellaneous'])) {
            return view('/transactions/miscellaneous_search');
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    public function miscellaneous($studentId) {
        if (Auth::user()->hasAnyPermission(['god permission', 'transact miscellaneous'])) {
            return view('/transactions/miscellaneous', [
                'studentId' => $studentId,
            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    public function getMiscPayables(Request $request) {
        return response()->json(MiscellaneousPayables::orderBy('Payable')->get());
    }

    public function transactMiscellaneous(Request $request) {
        $studentId = $request['StudentId'];
        $cashAmount = $request['cashAmount'];
        $checkNumber = $request['checkNumber'];
        $checkBank = $request['checkBank'];
        $checkAmount = $request['checkAmount'];
        $digitalNumber = $request['digitalNumber'];
        $digitalBank = $request['digitalBank'];
        $digitalAmount = $request['digitalAmount'];
        $totalPayables = $request['totalPayables'];
        $totalPayments = $request['totalPayments'];
        $orNumber = $request['ORNumber'];
        $details = $request['Details'];
        $transactionDetails = $request['TransactionDetails'];

        // determine mode of payment
        $modeOfPayment = '';
        if ($cashAmount != null) {
            $modeOfPayment .= 'Cash;';
        }
        if ($checkAmount != null) {
            $modeOfPayment .= 'Check;';
        }
        if ($digitalAmount != null) {
            $modeOfPayment .= 'Digital;';
        }

        // insert transaction
        $id = IDGenerator::generateIDandRandString();
        $transactions = new Transactions;
        $transactions->id = $id;
        $transactions->StudentId = $studentId;
        $transactions->PaymentFor = $details;
        $transactions->ModeOfPayment = $modeOfPayment;
        $transactions->ORNumber = $orNumber;
        $transactions->ORDate = date('Y-m-d');
        $transactions->CashAmount = $cashAmount;
        $transactions->CheckAmount = $checkAmount;
        $transactions->DigitalPaymentAmount = $digitalAmount;
        $transactions->TotalAmountPaid = $totalPayments;
        $transactions->UserId = Auth::id();
        $transactions->TransactionType = 'Miscellaneous';
        $transactions->save();

        // insert transaction details
        $concat = "";
        foreach($transactionDetails as $item) {
            $transactionDetails = new TransactionDetails;
            $transactionDetails->id = IDGenerator::generateIDandRandString();
            $transactionDetails->TransactionsId = $id;
            $transactionDetails->Particulars = $item['Payable'] . ' (' . $item["Quantity"] . ' x P' . $item["Price"] .')';
            $transactionDetails->Amount = $item['TotalAmount'];
            $transactionDetails->save();

            $concat .= "- " . $item['Payable'] . " (" . $item["Quantity"] . " x P" . $item["Price"] .")\n";
        }

        // send sms
        $student = Students::find($studentId);
        if ($student != null) {
            SmsMessages::createSmsWithStudentProvided($student, 
                "Holy Cross Academy System Notification\n\MISCELLANEOUS FEE has been paid for " . $student->FirstName . " " . $student->LastName . " amounting to " . number_format($totalPayments, 2) . ", with transaction number " . $orNumber . ", at " . date('M d, Y h:i A') . ", with the following items: \n\n" . 
                $concat, 
                2);
        } 

        return response()->json($id, 200);
    }

    public function printMiscellaneous($transactionId) {
        $transaction = Transactions::find($transactionId);

        if ($transaction != null) {
            $student = DB::table('Students')
                ->leftJoin('Towns', 'Students.Town', '=', 'Towns.id')
                ->leftJoin('Barangays', 'Students.Barangay', '=', 'Barangays.id')
                ->whereRaw("Students.id='" . $transaction->StudentId . "'")
                ->select('Students.*',
                    'Towns.Town as TownSpelled',
                    'Barangays.Barangay as BarangaySpelled')
                ->first();

            $classes = Classes::find($student->CurrentGradeLevel);

            $transactionDetails = TransactionDetails::where('TransactionsId', $transaction->id)->get();

            return view('/transactions/print_miscellaneous', [
                'transaction' => $transaction,
                'student' => $student,
                'transactionDetails' => $transactionDetails,
                'classes' => $classes,
            ]);
        } else {
            return abort('No transaction found!', 404);
        }
    }

    public function getTransactionHistory(Request $request) {
        $studentId = $request['StudentId'];

        $data = DB::table('Transactions')
            ->leftJoin('users', 'Transactions.UserId', '=', 'users.id')
            ->where('StudentId', $studentId)
            ->whereRaw("Transactions.Status IS NULL")
            ->select('Transactions.*', 'users.name')
            ->orderByDesc('ORDate')
            ->get();

        return response()->json($data, 200);
    }

    public function myDcr(Request $request) {
        if (Auth::user()->hasAnyPermission(['god permission', 'view my dcr'])) {
            return view('/transactions/my_dcr');
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    public function fetchPayments(Request $request) {
        $date = $request['Date'];

        $data['Payments'] = DB::table('Transactions')
            ->leftJoin('Students', 'Transactions.StudentId', '=', 'Students.id')
            ->whereRaw("Transactions.ORDate='" . $date . "' AND Transactions.Status IS NULL AND Transactions.UserId='" . Auth::id() . "'")
            ->select(
                'Transactions.*',
                'Students.FirstName',
                'Students.LastName',
                'Students.id AS StudentId'
            )
            ->orderBy('Transactions.created_at')
            ->get();

        $data['Cancelled'] = DB::table('Transactions')
            ->leftJoin('Students', 'Transactions.StudentId', '=', 'Students.id')
            ->whereRaw("Transactions.ORDate='" . $date . "' AND Transactions.Status='CANCELLED' AND Transactions.UserId='" . Auth::id() . "'")
            ->select(
                'Transactions.*',
                'Students.FirstName',
                'Students.LastName',
                'Students.id AS StudentId'
            )
            ->orderBy('Transactions.created_at')
            ->get();

        return response()->json($data, 200);
    }

    public function fetchTransactionDetails(Request $request) {
        $transactionId = $request['TransactionId'];

        return response()->json(
            TransactionDetails::where('TransactionsId', $transactionId)
                ->orderBy('created_at')
                ->get(),
            200
        );
    }

    public function fetchAllTransactionDetails(Request $request) {
        $date = $request['Date'];

        $data = DB::table('TransactionDetails')
            ->leftJoin('Transactions', 'Transactions.id', '=', 'TransactionDetails.TransactionsId')
            ->leftJoin('Students', 'Transactions.StudentId', '=', 'Students.id')
            ->whereRaw("Transactions.ORDate='" . $date . "' AND Transactions.Status IS NULL AND Transactions.UserId='" . Auth::id() . "'")
            ->select(
                'TransactionDetails.*',
                'Students.FirstName',
                'Students.LastName',
                'Transactions.ORNumber',
                'Students.id AS StudentId'
            )
            ->orderBy('Transactions.ORNumber')
            ->orderBy('Transactions.created_at')
            ->get();
            
        return response()->json($data, 200);
    }

    public function printMyDcr($date) {
        return view('/transactions/print_my_dcr', [
            'date' => $date
        ]);
    }

    public function allDcr(Request $request) {
        if (Auth::user()->hasAnyPermission(['god permission', 'view all dcr'])) {
            return view('/transactions/all_dcr', [

            ]);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    public function cancelTransaction(Request $request) {
        $id = $request['id'];
        $reason = $request['Reason'];

        Transactions::where('id', $id)
            ->update(['Status' => 'CANCELLED', 'Notes' => $reason]);

        $transaction = Transactions::find($id);

        // remove from tuitions payable
        if ($transaction != null && $transaction->PayablesId != null) {
            $payable = Payables::find($transaction->PayablesId);

            if ($payable != null) {
                $amount = $transaction->TotalAmountPaid != null ? floatval($transaction->TotalAmountPaid) : 0;

                if ($amount > 0) {
                    // update payable amount
                    $payableAmountPaid = $payable->AmountPaid != null ? $payable->AmountPaid : 0;
                    $payableBalance = $payable->Balance != null ? $payable->Balance : 0;

                    $payable->AmountPaid = $payableAmountPaid - $amount;
                    $payable->Balance = $payableBalance + $amount;
                    $payable->save();

                    // update tuitions breakdown
                    $tuitionBreakdown = TuitionsBreakdown::where('PayableId', $payable->id)
                        ->whereRaw("AmountPaid IS NOT NULL AND AmountPaid > 0")
                        ->orderByDesc('ForMonth')
                        ->get();

                    if ($tuitionBreakdown != null) {
                        $pdAmount = $amount;
                        foreach($tuitionBreakdown as $item) {
                            if ($pdAmount > 0) {
                                $tbPaidAmount = $item->AmountPaid != null ? floatval($item->AmountPaid) : 0;
                                $tbBalance = $item->Balance != null ? floatval($item->Balance) : 0;

                                if ($pdAmount >= $tbPaidAmount) {
                                    $item->AmountPaid = null;
                                    $item->Balance = $item->AmountPayable;
                                    $item->save();

                                    $pdAmount = $pdAmount - $tbPaidAmount;
                                } else {
                                    $dif = $tbPaidAmount - $pdAmount;

                                    $item->AmountPaid = $dif;
                                    $item->Balance = $tbBalance + $pdAmount;
                                    $item->save();

                                    $pdAmount = 0;
                                }
                            } else {
                                break;
                            }
                        }
                    }
                }
            }
        }

        return response()->json($id, 200);
    }

    public function getCashiers(Request $request) {
        $data = DB::table('Transactions')
            ->leftJoin('users', 'Transactions.UserId', '=', 'users.id')
            ->select('name', 'users.id')
            ->orderBy('name')
            ->groupBy('name', 'users.id')
            ->get();

        return response()->json($data, 200);
    }
    
    public function fetchAdminPayments(Request $request) {
        $from = $request['From'];
        $to = $request['To'];
        $cashier = $request['Cashier'];

        $data['Payments'] = DB::table('Transactions')
            ->leftJoin('Students', 'Transactions.StudentId', '=', 'Students.id')
            ->whereRaw("(Transactions.ORDate BETWEEN '" . $from . "' AND '" . $to . "') AND Transactions.Status IS NULL AND Transactions.UserId='" . $cashier . "'")
            ->select(
                'Transactions.*',
                'Students.FirstName',
                'Students.LastName',
                'Students.id AS StudentId'
            )
            ->orderBy('Transactions.created_at')
            ->get();

        $data['Cancelled'] = DB::table('Transactions')
            ->leftJoin('Students', 'Transactions.StudentId', '=', 'Students.id')
            ->whereRaw("(Transactions.ORDate BETWEEN '" . $from . "' AND '" . $to . "') AND Transactions.Status='CANCELLED' AND Transactions.UserId='" . $cashier . "'")
            ->select(
                'Transactions.*',
                'Students.FirstName',
                'Students.LastName',
                'Students.id AS StudentId'
            )
            ->orderBy('Transactions.created_at')
            ->get();

        return response()->json($data, 200);
    }

    public function fetchAllAdminTransactionDetails(Request $request) {
        $from = $request['From'];
        $to = $request['To'];
        $cashier = $request['Cashier'];

        $data = DB::table('TransactionDetails')
            ->leftJoin('Transactions', 'Transactions.id', '=', 'TransactionDetails.TransactionsId')
            ->leftJoin('Students', 'Transactions.StudentId', '=', 'Students.id')
            ->whereRaw("(Transactions.ORDate BETWEEN '" . $from . "' AND '" . $to . "') AND Transactions.Status IS NULL AND Transactions.UserId='" . $cashier . "'")
            ->select(
                'TransactionDetails.*',
                'Students.FirstName',
                'Students.LastName',
                'Transactions.ORNumber',
                'Students.id AS StudentId'
            )
            ->orderBy('Transactions.ORNumber')
            ->orderBy('Transactions.created_at')
            ->get();
            
        return response()->json($data, 200);
    }

    public function repopulatePayables(Request $request) {
        $classId = $request['ClassId'];

        $students = Students::where('CurrentGradeLevel', $classId)->get();

        $class = Classes::find($classId);

        $sy = SchoolYear::find($class->SchoolYearId);

        if ($class != null) {
            $classRepo = ClassesRepo::where('Year', $class->Year)
                ->where('Section', $class->Section)
                ->where('Strand', $class->Strand)
                ->where('Semester', $class->Semester)
                ->first();

            // loop students
            foreach($students as $item) {
                if ($classRepo != null) {
                    $tuitionInclusions = TuitionInclusions::where('ClassRepoId', $classRepo->id)
                        ->where('FromSchool', $item->FromSchool != null ? $item->FromSchool : 'Private')
                        ->get();
                } else {
                    $tuitionInclusions = [];
                }

                /**
                 * ==========================
                 * PAYABLE INCLUSIONS
                 * ==========================
                 */
                $payable = Payables::where('StudentId', $item->id)
                    ->where('ClassId', $classId)
                    ->first();

                // repopulate payable inclusions
                if ($payable != null && count($tuitionInclusions) > 0) {
                    // PayableInclusions::where('PayableId', $payable->id)->delete();
                    // insert tuition inclusions to payable inclusions
                    foreach($tuitionInclusions as $ti) {
                        $pi = new PayableInclusions;
                        $pi->id = IDGenerator::generateIDandRandString();
                        $pi->ItemName = $ti->ItemName;
                        $pi->Amount = $ti->Amount;
                        $pi->PayableId = $payable->id;
                        $pi->save();
                    }
                }

                /**
                 * ==========================
                 * TUITIONS BREAKDOWN
                 * ==========================
                 */
                
                if ($payable != null) {
                    // TuitionsBreakdown::where('PayableId', $payable->id)->delete();
                    // create tuitions breakdown
                    $monthsToPay = 10;
                    for ($i=0; $i<$monthsToPay; $i++) {
                        $syStartDate = $sy->MonthStart != null ? $sy->MonthStart : date('Y-m-d');
                        $tuitionBreakdown = new TuitionsBreakdown;
                        $tuitionBreakdown->id = IDGenerator::generateIDandRandString();
                        $tuitionBreakdown->ForMonth = date('Y-m-01', strtotime($syStartDate . ' +' . ($i+1) . ' months'));
                        $tuitionBreakdown->PayableId = $payable->id;

                        $amntPayable = $payable->AmountPayable > 0 ? ($payable->AmountPayable / $monthsToPay) : 0;

                        $tuitionBreakdown->AmountPayable = $amntPayable;
                        $tuitionBreakdown->Payable = $amntPayable;
                        $tuitionBreakdown->Balance = $amntPayable;
                        $tuitionBreakdown->save();
                    }
                }
            }
        }

        return response()->json($class, 200);
    }

    public function getPayableInclusions(Request $request) {
        $payableId = $request['PayableId'];

        return response()->json(PayableInclusions::where('PayableId', $payableId)->get(), 200);
    }

    public function updateORNumber(Request $request) {
        $id = $request['id'];
        $newORNumber = $request['NewORNumber'];

        Transactions::where('id', $id)
            ->update(['ORNumber' => $newORNumber]);

        return response()->json('ok', 200);
    }

    public function removePayableInclusion(Request $request) {
        $id = $request['id'];

        $inc = PayableInclusions::find($id);

        if ($inc != null) {
            $inc->delete();

            $payable = Payables::find($inc->PayableId);

            if ($payable != null) {
                $incAmount = $inc->Amount != null ? floatval($inc->Amount) : 0;

                // update payable
                $payablePayable = $payable->Payable != null ? floatval($payable->Payable) : 0;
                $payableAmountPayable  = $payable->AmountPayable != null ? floatval($payable->AmountPayable) : 0;
                $payableBalance  = $payable->Balance != null ? floatval($payable->Balance) : 0;

                $payable->Payable = $payablePayable - $incAmount;
                $payable->AmountPayable = $payableAmountPayable - $incAmount;
                $payable->Balance = $payableBalance - $incAmount;
                $payable->save();

                // update payable tuitions breakdown
                $tuitionsBreakdown = TuitionsBreakdown::where('PayableId', $payable->id)->whereRaw("AmountPaid IS NULL OR AmountPaid = 0")->get();
                if ($tuitionsBreakdown != null) {
                    $count = count($tuitionsBreakdown);

                    if ($count > 0) {
                        $amountDistributable = round(($incAmount / $count), 2);
                    
                        foreach($tuitionsBreakdown as $item) {
                            $item->AmountPayable = floatval($item->AmountPayable) - $amountDistributable;
                            $item->Balance = $item->AmountPayable;
                            $item->save();
                        }
                    }
                }
            }
        }

        return response()->json($inc, 200);
    }
    
    public function addPayableInclusion(Request $request) {
        $itemName = $request['ItemName'];
        $itemAmount = $request['Amount'];
        $payableId = $request['PayableId'];

        $inc = new PayableInclusions;
        $inc->id = IDGenerator::generateIDandRandString();
        $inc->ItemName = $itemName;
        $inc->Amount = $itemAmount;
        $inc->PayableId = $payableId;
        $inc->save();

        // save payable
        $payable = Payables::find($payableId);

        if ($payable != null) {
            $incAmount = $itemAmount != null ? floatval($itemAmount) : 0;

            // update payable
            $payablePayable = $payable->Payable != null ? floatval($payable->Payable) : 0;
            $payableAmountPayable  = $payable->AmountPayable != null ? floatval($payable->AmountPayable) : 0;
            $payableBalance  = $payable->Balance != null ? floatval($payable->Balance) : 0;

            $payable->Payable = $payablePayable + $incAmount;
            $payable->AmountPayable = $payableAmountPayable + $incAmount;
            $payable->Balance = $payableBalance + $incAmount;
            $payable->save();

            // update payable tuitions breakdown
            $tuitionsBreakdown = TuitionsBreakdown::where('PayableId', $payableId)->whereRaw("AmountPaid IS NULL OR AmountPaid = 0")->get();
            if ($tuitionsBreakdown != null) {
                $count = count($tuitionsBreakdown);

                if ($count > 0) {
                    $amountDistributable = round(($incAmount / $count), 2);
                
                    foreach($tuitionsBreakdown as $item) {
                        $item->AmountPayable = floatval($item->AmountPayable) + $amountDistributable;
                        $item->Balance = $item->AmountPayable;
                        $item->save();
                    }
                }
            }
        }

        return response()->json('ok', 200);
    }
}
