<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSmsMessagesRequest;
use App\Http\Requests\UpdateSmsMessagesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\SmsMessagesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SmsMessages;
use App\Models\Classes;
use App\Models\Students;
use Flash;

class SmsMessagesController extends AppBaseController
{
    /** @var SmsMessagesRepository $smsMessagesRepository*/
    private $smsMessagesRepository;

    public function __construct(SmsMessagesRepository $smsMessagesRepo)
    {
        $this->middleware('auth');
        $this->smsMessagesRepository = $smsMessagesRepo;
    }

    /**
     * Display a listing of the SmsMessages.
     */
    public function index(Request $request)
    {
        $smsMessages = $this->smsMessagesRepository->paginate(10);

        return view('sms_messages.index')
            ->with('smsMessages', $smsMessages);
    }

    /**
     * Show the form for creating a new SmsMessages.
     */
    public function create()
    {
        return view('sms_messages.create');
    }

    /**
     * Store a newly created SmsMessages in storage.
     */
    public function store(CreateSmsMessagesRequest $request)
    {
        $input = $request->all();

        $smsMessages = $this->smsMessagesRepository->create($input);

        Flash::success('Sms Messages saved successfully.');

        return redirect(route('smsMessages.index'));
    }

    /**
     * Display the specified SmsMessages.
     */
    public function show($id)
    {
        $smsMessages = $this->smsMessagesRepository->find($id);

        if (empty($smsMessages)) {
            Flash::error('Sms Messages not found');

            return redirect(route('smsMessages.index'));
        }

        return view('sms_messages.show')->with('smsMessages', $smsMessages);
    }

    /**
     * Show the form for editing the specified SmsMessages.
     */
    public function edit($id)
    {
        $smsMessages = $this->smsMessagesRepository->find($id);

        if (empty($smsMessages)) {
            Flash::error('Sms Messages not found');

            return redirect(route('smsMessages.index'));
        }

        return view('sms_messages.edit')->with('smsMessages', $smsMessages);
    }

    /**
     * Update the specified SmsMessages in storage.
     */
    public function update($id, UpdateSmsMessagesRequest $request)
    {
        $smsMessages = $this->smsMessagesRepository->find($id);

        if (empty($smsMessages)) {
            Flash::error('Sms Messages not found');

            return redirect(route('smsMessages.index'));
        }

        $smsMessages = $this->smsMessagesRepository->update($request->all(), $id);

        Flash::success('Sms Messages updated successfully.');

        return redirect(route('smsMessages.index'));
    }

    /**
     * Remove the specified SmsMessages from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $smsMessages = $this->smsMessagesRepository->find($id);

        if (empty($smsMessages)) {
            Flash::error('Sms Messages not found');

            return redirect(route('smsMessages.index'));
        }

        $this->smsMessagesRepository->delete($id);

        Flash::success('Sms Messages deleted successfully.');

        return redirect(route('smsMessages.index'));
    }

    public function smsNotifiers(Request $request) {
        if (Auth::user()->hasAnyPermission(['god permission', 'create notifiers'])) {
            return view('/sms_messages/notifier');
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    public function getGrades(Request $request) {
        $data = DB::table('ClassesRepo')
            ->orderBy('Year')
            ->orderBy('Section')
            ->get();

        return response()->json($data, 200);
    }

    public function sendSMS(Request $request) {
        $recipients = $request['Recipients'];
        $message = $request['Message'];

        if ($recipients != null) {
            foreach($recipients as $item) {
                $class = Classes::where('Year', $item['Year'])
                    ->where('Section', $item['Section'])
                    ->where('Strand', $item['Strand'])
                    ->where('Semester', $item['Semester'])
                    ->orderByDesc('created_at')
                    ->first();

                if ($class != null) {
                    $students = Students::where('CurrentGradeLevel', $class->id)
                        ->whereRaw("ContactNumber IS NOT NULL AND LEN(ContactNumber) > 9")
                        ->get();

                    if ($students != null) {
                        foreach($students as $s) {
                            SmsMessages::createSmsWithStudentProvided($s, $message, 2);
                        }
                    }
                }
            }
        }

        return response()->json('ok', 200);
    }

    public function history(Request $request) {
        if (Auth::user()->hasAnyPermission(['god permission', 'create notifiers'])) {
            return view('/sms_messages/history');
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    public function getBatchSmsHistory(Request $request) {
        $data = DB::table('SMSMessages')
            ->whereRaw("Priority > 1 AND SmsSent IS NOT NULL")
            ->select('Message', 
                DB::raw("COUNT(id) AS TotalRecipients")
            )
            ->groupBy('Message')
            ->having(DB::raw("COUNT(id)"), '>', 1)
            ->get();

        return response()->json($data, 200);
    }

    public function getActiveBatchSms(Request $request) {
        $data = DB::table('SMSMessages')
            ->whereRaw("Priority > 1 AND SmsSent IS NULL")
            ->select(
                'Message', 
                DB::raw("(SELECT COUNT(s.id) FROM SMSMessages s WHERE s.Message=SMSMessages.Message) AS TotalRecipients"),
                DB::raw("(SELECT COUNT(s.id) FROM SMSMessages s WHERE s.Message=SMSMessages.Message AND s.SmsSent IS NOT NULL) AS TotalSent")
            )
            ->groupBy('Message')
            ->having(DB::raw("COUNT(id)"), '>', 1)
            ->get();

        return response()->json($data, 200);
    }
}
