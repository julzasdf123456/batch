<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payables extends Model
{
    public $table = 'Payables';

    public $fillable = [
        'id',
        'StudentId',
        'PaymentFor',
        'AmountPayable',
        'AmountPaid',
        'Balance',
        'Category',
        'SchoolYear',
        'Payable',
        'DiscountAmount',
        'ClassId'
    ];

    protected $casts = [
        'id' => 'string',
        'StudentId' => 'string',
        'PaymentFor' => 'string',
        'AmountPayable' => 'decimal:2',
        'AmountPaid' => 'decimal:2',
        'Balance' => 'decimal:2',
        'Category' => 'string',
        'SchoolYear' => 'string',
        'Payable' => 'string',
        'DiscountAmount' => 'string',
        'ClassId' => 'string',
    ];

    public static array $rules = [
        'id' => 'string',
        'StudentId' => 'nullable|string|max:50',
        'PaymentFor' => 'nullable|string|max:1000',
        'AmountPayable' => 'nullable|numeric',
        'AmountPaid' => 'nullable|numeric',
        'Balance' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Category' => 'nullable|string',
        'SchoolYear' => 'nullable|string',
        'Payable' => 'nullable|string',
        'DiscountAmount' => 'nullable|string',
        'ClassId' => 'nullable|string',
    ];

    
}
