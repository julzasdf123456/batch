<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\BarcodeAttendance;
use App\Models\IDGenerator;
use App\Models\SmsMessages;
use App\Models\Teachers;
use App\Models\Students;

class BarcodeScan extends Controller {
    public function punchStudent(Request $request) {
        date_default_timezone_set('Asia/Manila');

        $input = $request->all();

        // $to = date('Y-m-d H:i:s');
        // $from = date('Y-m-d H:i:s', strtotime($to . " -5 hours"));

        // configure punch type
        $currentDate = date('Y-m-d');
        $morningThresholdTime = '11:59';
        $afternoonThresholdTime = '12:00';
        $morningInThreshold = date('Y-m-d H:i:s', strtotime($currentDate . ' ' . $morningThresholdTime));
        $afternoonInThreshold = date('Y-m-d H:i:s', strtotime($currentDate . ' ' . $afternoonThresholdTime));

        if (strtotime($morningInThreshold) < strtotime(date('Y-m-d H:i:s'))) {
            $input['PunchType'] = 'OUT';

            $to = date('Y-m-d H:i:s', strtotime($currentDate . ' 21:00'));
            $from = date('Y-m-d H:i:s', strtotime($afternoonInThreshold));
        } else {
            $input['PunchType'] = 'IN';

            $to = date('Y-m-d H:i:s', strtotime($morningInThreshold));
            $from = date('Y-m-d H:i:s', strtotime($currentDate . ' 04:00'));
        }

        if ($input['Type'] === 'Teacher') {
            /**
             * TEACHERS
             */
            $bCodeCheck = DB::table('BarcodeAttendance')
                ->whereRaw("StudentId='" . $input['StudentId'] . "' AND BarcodeId='Teacher' AND (created_at BETWEEN '" . $from . "' AND '" . $to . "') AND PunchType='" . $input['PunchType'] . "'")
                ->first();

            if ($bCodeCheck != null) {
                // return response()->json('Teacher already logged ' . ($input['PunchType'] != null && $input['PunchType']==='IN' ? 'OUT' : 'IN'), 400);
                return response()->json('Teacher already logged ' . $input['PunchType'], 400);
            } else {
                $input['BarcodeId'] = $input['Type'];

                $barcodeAttendance = BarcodeAttendance::create($input);

                // save sms for sending
                if (isset($input['ContactNumber']) && $input['ContactNumber'] != null && strlen($input['ContactNumber']) >= 10) {
                    //get student
                    $teacher = Teachers::find($input['StudentId']);

                    if ($teacher != null) {
                        SmsMessages::create([
                            'id' => IDGenerator::generateIDandRandString(),
                            'ContactNumber' => $input['ContactNumber'],
                            'Message' => env("APP_COMPANY") . " System Notification\n\n" .
                                $teacher->FullName . " has logged " . $input['PunchType'] . " of/to the campus at " . date('D, M d, Y h:i A'),
                            'AIFacilitator' => 'Reeve',
                            'Source' => 'batch.ID',
                            'Priority' => 1,
                        ]);
                    }
                }

                return response()->json($barcodeAttendance, 200);
            }
        } else {
            /**
             * STUDENTS
             */
            $bCodeCheck = DB::table('BarcodeAttendance')
                ->whereRaw("StudentId='" . $input['StudentId'] . "' AND BarcodeId IS NULL AND (created_at BETWEEN '" . $from . "' AND '" . $to . "') AND PunchType='" . $input['PunchType'] . "'")
                ->first();

            if ($bCodeCheck != null) {
                // return response()->json('Student already logged ' . ($input['PunchType'] != null && $input['PunchType']==='IN' ? 'OUT' : 'IN'), 400);
                return response()->json('Student already logged ' . $input['PunchType'], 400);
            } else {
                $input['BarcodeId'] = null;

                $barcodeAttendance = BarcodeAttendance::create($input);

                // save sms for sending
                if (isset($input['ContactNumber']) && $input['ContactNumber'] != null && strlen($input['ContactNumber']) >= 10) {
                    //get student
                    $student = Students::find($input['StudentId']);

                    if ($student != null) {
                        SmsMessages::create([
                            'id' => IDGenerator::generateIDandRandString(),
                            'ContactNumber' => $input['ContactNumber'],
                            'Message' => env("APP_COMPANY") . " System Notification\n\n" .
                                $student->FirstName . " " . $student->LastName . " has logged " . $input['PunchType'] . " of/to the campus at " . date('D, M d, Y h:i A'),
                            'AIFacilitator' => 'Reeve',
                            'Source' => 'batch.ID',
                            'Priority' => 1,
                        ]);
                    }
                }

                return response()->json($barcodeAttendance, 200);
            }
        }
    }
}