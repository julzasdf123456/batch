<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    public $table = 'Transactions';

    public $fillable = [
        'id',
        'PayablesId',
        'StudentId',
        'PaymentFor',
        'ModeOfPayment',
        'ORNumber',
        'ORDate',
        'CashAmount',
        'CheckAmount',
        'DigitalPaymentAmount',
        'TotalAmountPaid',
        'Notes',
        'UserId',
        'Period',
        'Status',
        'TransactionType'
    ];

    protected $casts = [
        'id' => 'string',
        'PayablesId' => 'string',
        'StudentId' => 'string',
        'PaymentFor' => 'string',
        'ModeOfPayment' => 'string',
        'ORNumber' => 'string',
        'ORDate' => 'date',
        'CashAmount' => 'decimal:2',
        'CheckAmount' => 'decimal:2',
        'DigitalPaymentAmount' => 'decimal:2',
        'TotalAmountPaid' => 'decimal:2',
        'Notes' => 'string',
        'UserId' => 'string',
        'Period' => 'string',
        'Status' => 'string',
        'TransactionType' => 'string',
    ];

    public static array $rules = [
        'id' => 'string',
        'PayablesId' => 'nullable|string|max:80',
        'StudentId' => 'nullable|string|max:80',
        'PaymentFor' => 'nullable|string|max:1000',
        'ModeOfPayment' => 'nullable|string|max:80',
        'ORNumber' => 'nullable|string|max:50',
        'ORDate' => 'nullable',
        'CashAmount' => 'nullable|numeric',
        'CheckAmount' => 'nullable|numeric',
        'DigitalPaymentAmount' => 'nullable|numeric',
        'TotalAmountPaid' => 'nullable|numeric',
        'Notes' => 'nullable|string|max:1050',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'UserId' => 'nullable|string',
        'Period' => 'nullable|string',
        'Status' => 'nullable|string',
        'TransactionType' => 'nullable|string',
    ];

    
}
