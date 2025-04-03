<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObservedValues extends Model
{
    public $table = 'ObservedValues';

    protected $connection = 'sqlsrv';

    protected $primaryKey = 'id';

    public static $MAKADIOS_1 = 'MAKADIOS_1';
    public static $MAKADIOS_2 = 'MAKADIOS_2';
    public static $MAKATAO_1 = 'MAKATAO_1';
    public static $MAKATAO_2 = 'MAKATAO_2';
    public static $MAKAKALIKASAN_1 = 'MAKAKALIKASAN_1';
    public static $MAKABANSA_1 = 'MAKABANSA_1';
    public static $MAKABANSA_2 = 'MAKABANSA_2';

    public $fillable = [
        'id',
        'StudentId',
        'ClassId',
        'ObservedValue',
        'FirstQuarter',
        'SecondQuarter',
        'ThirdQuarter',
        'FourthQuarter',
    ];

    protected $casts = [
        'StudentId' => 'string',
        'ClassId' => 'string',
        'ObservedValue' => 'string',
        'FirstQuarter' => 'string',
        'SecondQuarter'=> 'string',
        'ThirdQuarter'=> 'string',
        'FourthQuarter' => 'string',
    ];

    public static array $rules = [
        'StudentId' => 'nullable|string|max:50',
        'ClassId' => 'nullable|string|max:50',
        'ObservedValue' => 'nullable|string|max:50',
        'FirstQuarter' => 'nullable|string|max:50',
        'SecondQuarter' => 'nullable|string|max:50',
        'ThirdQuarter' => 'nullable|string|max:50',
        'FourthQuarter' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];
    
}
