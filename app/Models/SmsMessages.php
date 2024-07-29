<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\IDGenerator;
use App\Models\Students;

class SmsMessages extends Model
{
    public $table = 'SMSMessages';

    public $fillable = [
        'id',
        'ContactNumber',
        'Message',
        'AIFacilitator',
        'Source',
        'Priority',
        'SmsSent'
    ];

    protected $casts = [
        'id' => 'string',
        'ContactNumber' => 'string',
        'Message' => 'string',
        'AIFacilitator' => 'string',
        'Source' => 'string',
        'SmsSent' => 'string'
    ];

    public static array $rules = [
        'ContactNumber' => 'nullable|string|max:60',
        'Message' => 'nullable|string|max:1500',
        'AIFacilitator' => 'nullable|string|max:50',
        'Source' => 'nullable|string|max:50',
        'Priority' => 'nullable',
        'SmsSent' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public static function createSmsWithStudentProvided($studentData, $message, $priority) {
        if ($studentData != null) {
            if (isset($studentData->ContactNumber) && $studentData->ContactNumber != null && (strlen($studentData->ContactNumber) >= 10 && strlen($studentData->ContactNumber) < 13)) {
                SmsMessages::create([
                    'id' => IDGenerator::generateIDandRandString(),
                    'ContactNumber' => $studentData->ContactNumber,
                    'Message' => $message,
                    'AIFacilitator' => 'Reeve',
                    'Source' => 'batch.ID',
                    'Priority' => $priority,
                ]);
            }
        }
    }

    public static function createSmsWithoutStudentProvided($studentId, $message, $priority) {
        $studentData = Students::find($studentId);
        if ($studentData != null) {
            if (isset($studentData->ContactNumber) && $studentData->ContactNumber != null && strlen($studentData->ContactNumber) >= 10) {
                SmsMessages::create([
                    'id' => IDGenerator::generateIDandRandString(),
                    'ContactNumber' => $studentData->ContactNumber,
                    'Message' => $message,
                    'AIFacilitator' => 'Reeve',
                    'Source' => 'batch.ID',
                    'Priority' => $priority,
                ]);
            }
        }
    }
}
