<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayableUpdateLogs extends Model
{
    public $table = 'PayableUpdateLogs';

    protected $connection = 'sqlsrv';

    public $fillable = [
        'id',
        'PayableId',
        'UserId',
        'OGTotalPayable',
        'OGPaidAmount',
        'OGBalance',
        'NewTotalPayable',
        'NewPaidAmount',
        'NewBalance'
    ];

    protected $casts = [
        'PayableId' => 'string',
        'UserId' => 'string',
        'OGTotalPayable' => 'decimal:2',
        'OGPaidAmount' => 'decimal:2',
        'OGBalance' => 'decimal:2',
        'NewTotalPayable' => 'decimal:2',
        'NewPaidAmount' => 'decimal:2',
        'NewBalance' => 'decimal:2'
    ];

    public static array $rules = [
        'PayableId' => 'nullable|string|max:50',
        'UserId' => 'nullable|string|max:50',
        'OGTotalPayable' => 'nullable|numeric',
        'OGPaidAmount' => 'nullable|numeric',
        'OGBalance' => 'nullable|numeric',
        'NewTotalPayable' => 'nullable|numeric',
        'NewPaidAmount' => 'nullable|numeric',
        'NewBalance' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
