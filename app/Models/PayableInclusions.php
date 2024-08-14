<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayableInclusions extends Model
{
    public $table = 'PayableInclusions';

    public $fillable = [
        'id',
        'ItemName',
        'Amount',
        'PayableId',
        'NotDeductedMonthly'
    ];

    protected $casts = [
        'id' => 'string',
        'ItemName' => 'string',
        'Amount' => 'string',
        'PayableId' => 'string',
        'NotDeductedMonthly' => 'string',
    ];

    public static array $rules = [
        'ItemName' => 'nullable|string|max:500',
        'Amount' => 'nullable|string',
        'PayableId' => 'nullable|string|max:80',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'NotDeductedMonthly' => 'nullable|string',
    ];

    
}
