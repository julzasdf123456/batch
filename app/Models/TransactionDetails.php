<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetails extends Model
{
    public $table = 'TransactionDetails';

    public $fillable = [
        'id',
        'TransactionsId',
        'Particulars',
        'AccountNumber',
        'Amount',
        'ForMonth',
        'FlushedToTuition'
    ];

    protected $casts = [
        'id' => 'string',
        'TransactionsId' => 'string',
        'Particulars' => 'string',
        'AccountNumber' => 'string',
        'Amount' => 'string',
        'ForMonth' => 'string',
        'FlushedToTuition' => 'string',
    ];

    public static array $rules = [
        'id' => 'string',
        'TransactionsId' => 'nullable|string|max:80',
        'Particulars' => 'nullable|string|max:700',
        'AccountNumber' => 'nullable|string|max:50',
        'Amount' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ForMonth' => 'nullable|string',
        'FlushedToTuition' => 'nullable|string',
    ];

    
}
