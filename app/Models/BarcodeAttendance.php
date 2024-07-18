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

    public static function sf2JuniorDateHeaderColumnArray() {
        return [
            'F6', 'H6', 'I6', 'J6', 'K6', 'L6',
            'M6', 'O6', 'P6', 'Q6', 'R6', 'S6',
            'T6', 'V6', 'W6', 'X6', 'Z6', 'AA6',
            'AC6', 'AE6', 'AF6', 'AG6', 'AH6', 'AI6',
            'AJ6', 'AK6', 'AM6', 'AN6', 'AO6', 'AP6'
        ];
    }

    public static function sf2JuniorDateHeaderColumnArrayNoRowNum() {
        return [
            'F', 'H', 'I', 'J', 'K', 'L',
            'M', 'O', 'P', 'Q', 'R', 'S',
            'T', 'V', 'W', 'X', 'Z', 'AA',
            'AC', 'AE', 'AF', 'AG', 'AH', 'AI',
            'AJ', 'AK', 'AM', 'AN', 'AO', 'AP'
        ];
    }

    public static function getAttendanceProfileFromStudentAndDate($array, $studentId, $date) {
        return array_filter($array, function($obj) use ($studentId, $date) {
            return $obj->StudentId == $studentId && date('Y-m-d', strtotime($obj->created_at)) == $date;
        });
    }

    public static function sf2SeniorDateHeaderColumnArray() {
        return [
            'K16', 'M16', 'N16', 'O16', 'S16', 'T16',
            'W16', 'X16', 'AA16', 'AB16', 'AC16', 'AD16',
            'AG16', 'AJ16', 'AK16', 'AN16', 'AP16', 'AQ16',
            'AS16', 'AU16', 'AV16', 'AX16', 'AY16', 'AZ16',
            'BA16', 'BC16', 'BF16', 'BH16', 'BI16', 'BJ16'
        ];
    }

    public static function sf2SeniorDateHeaderColumnArrayNoRowNum() {
        return [
            'K', 'M', 'N', 'O', 'S', 'T',
            'W', 'X', 'AA', 'AB', 'AC', 'AD',
            'AG', 'AJ', 'AK', 'AN', 'AP', 'AQ',
            'AS', 'AU', 'AV', 'AX', 'AY', 'AZ',
            'BA', 'BC', 'BF', 'BH', 'BI', 'BJ'
        ];
    }
}
