<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarcodeAttendance extends Model
{
    public $table = 'BarcodeAttendance';

    public $fillable = [
        'id',
        'StudentId',
        'PunchType',
        'BarcodeId',
        'SmsSent',
        'ContactNumber',
    ];

    protected $casts = [
        'id' => 'string',
        'StudentId' => 'string',
        'PunchType' => 'string',
        'BarcodeId' => 'string',
        'SmsSent' => 'string',
        'ContactNumber' => 'string',
    ];

    public static array $rules = [
        'StudentId' => 'nullable|string|max:50',
        'PunchType' => 'nullable|string|max:50',
        'BarcodeId' => 'nullable|string|max:50',
        'SmsSent' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ContactNumber' => 'nullable|string',
    ];

    
}
