<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TuitionsBreakdown extends Model
{
    public $table = 'TuitionsBreakdown';

    public $fillable = [
        'id',
        'ForMonth',
        'PayableId',
        'AmountPayable',
        'AmountPaid',
        'Balance',
        'TransactionId',
        'Payable',
        'Discount',
    ];

    protected $casts = [
        'id' => 'string',
        'ForMonth' => 'date',
        'PayableId' => 'string',
        'AmountPayable' => 'string',
        'AmountPaid' => 'string',
        'Balance' => 'string',
        'TransactionId' => 'string',
        'Payable' => 'string',
        'Discount' => 'string',
    ];

    public static array $rules = [
        'ForMonth' => 'nullable',
        'PayableId' => 'nullable|string|max:90',
        'AmountPayable' => 'nullable|numeric',
        'AmountPaid' => 'nullable|numeric',
        'Balance' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'TransactionId' => 'nullable|string',
        'Payable' => 'nullable|string',
        'Discount' => 'nullable|string',
    ];

    
}
