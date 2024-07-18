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
        return view('/transactions/enrollment', [

        ]);
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
            return response()->json(null, 200);
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

        // add tuition fee payables
        $class = Classes::find($classId);
        if ($class != null) {
            $classRepo = ClassesRepo::where('Year', $class->Year)
                ->where('Section', $class->Section)
                ->first();
            
            $sy = SchoolYear::find($class->SchoolYearId);

            if ($classRepo != null) {
                $baseTuition = $classRepo->BaseTuitionFee;

                $payableId = IDGenerator::generateIDandRandString();
                $tuitionPayable = new Payables;
                $tuitionPayable->id = $payableId;
                $tuitionPayable->StudentId = $studentId;
                $tuitionPayable->PaymentFor = 'Tuition Fee for ' . ($sy != null ? $sy->SchoolYear : '(no school year declared)');
                $tuitionPayable->Category = 'Tuition Fees';
                $tuitionPayable->SchoolYear = $sy->SchoolYear;

                if ($baseTuition != null) {
                    // copy base tuition fee if declared in classes
                    $tuitionPayable->Payable = $baseTuition;
                    $tuitionPayable->AmountPayable = $baseTuition;
                    $tuitionPayable->Balance = $baseTuition;
                } else {
                    // get tuition per subject if not declared in classes
                    $totalSubjectTuition = DB::table('SubjectClasses')
                        ->leftJoin('Subjects', 'SubjectClasses.SubjectId', '=', 'Subjects.id')
                        ->whereRaw("SubjectClasses.ClassRepoId='" . $classRepo->id . "'")
                        ->select(
                            DB::raw("SUM(Subjects.CourseFee) AS Total")
                        )
                        ->first();

                    if ($totalSubjectTuition != null) {
                        $tuitionPayable->Payable = $totalSubjectTuition->Total;
                        $tuitionPayable->AmountPayable = $totalSubjectTuition->Total;
                        $tuitionPayable->Balance = $totalSubjectTuition->Total;
                    } else {
                        $tuitionPayable->Payable = 0.0;
                        $tuitionPayable->AmountPayable = 0.0;
                        $tuitionPayable->Balance = 0.0;
                    }
                }

                // create payable tuition inclusion
                $tuitionInclusions = TuitionInclusions::where('ClassRepoId', $classRepo->id)->get();
                if ($tuitionInclusions != null) {
                    foreach($tuitionInclusions as $item) {
                        $payableInclusions = new PayableInclusions;
                        $payableInclusions->id = IDGenerator::generateIDandRandString();
                        $payableInclusions->PayableId = $payableId;
                        $payableInclusions->ItemName = $item->ItemName;
                        $payableInclusions->Amount = $item->Amount;
                        $payableInclusions->save();
                    }
                }

                // create tuitions breakdown
                $monthsToPay = 10;
                for ($i=0; $i<$monthsToPay; $i++) {
                    $syStartDate = $sy->MonthStart != null ? $sy->MonthStart : date('Y-m-d');
                    $tuitionBreakdown = new TuitionsBreakdown;
                    $tuitionBreakdown->id = IDGenerator::generateIDandRandString();
                    $tuitionBreakdown->ForMonth = date('Y-m-01', strtotime($syStartDate . ' +' . ($i+1) . ' months'));
                    $tuitionBreakdown->PayableId = $payableId;

                    $amntPayable = $tuitionPayable->AmountPayable > 0 ? ($tuitionPayable->AmountPayable / $monthsToPay) : 0;

                    $tuitionBreakdown->AmountPayable = $amntPayable;
                    $tuitionBreakdown->Payable = $amntPayable;
                    $tuitionBreakdown->Balance = $amntPayable;
                    $tuitionBreakdown->save();
                }

                $tuitionPayable->save();
            }
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

            $transactionDetails = TransactionDetails::where('TransactionsId', $transaction->id)->get();

            return view('/transactions/print_enrollment', [
                'transaction' => $transaction,
                'student' => $student,
                'transactionDetails' => $transactionDetails,
            ]);
        } else {
            return abort('No transaction found!', 404);
        }
    }

    public function tuitions($studentId) {
        return view('/transactions/tuitions', [
            'studentId' => $studentId,
        ]);
    }

    public function tuitionsSearch(Request $request) {
        return view('/transactions/tuitions_search');
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

            return view('/transactions/print_tuition', [
                'transaction' => $transaction,
                'student' => $student,
                'transactionDetails' => $transactionDetails,
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
        return view('/transactions/miscellaneous_search');
    }

    public function miscellaneous($studentId) {
        return view('/transactions/miscellaneous', [
            'studentId' => $studentId,
        ]);
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

            $transactionDetails = TransactionDetails::where('TransactionsId', $transaction->id)->get();

            return view('/transactions/print_miscellaneous', [
                'transaction' => $transaction,
                'student' => $student,
                'transactionDetails' => $transactionDetails,
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
            ->select('Transactions.*', 'users.name')
            ->orderByDesc('ORDate')
            ->get();

        return response()->json($data, 200);
    }

    public function myDcr(Request $request) {
        return view('/transactions/my_dcr');
    }

    public function fetchPayments(Request $request) {
        $date = $request['Date'];

        $data = DB::table('Transactions')
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
}
