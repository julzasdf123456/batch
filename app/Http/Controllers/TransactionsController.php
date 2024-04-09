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

        return response()->json('ok', 200);
    }
}
