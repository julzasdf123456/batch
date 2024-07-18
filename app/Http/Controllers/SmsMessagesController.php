<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSmsMessagesRequest;
use App\Http\Requests\UpdateSmsMessagesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\SmsMessagesRepository;
use Illuminate\Http\Request;
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
}
