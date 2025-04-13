<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiscellaneousPayables extends Model
{
    public $table = 'MiscellaneousPayables';

    public $fillable = [
        'id',
        'Payable',
        'DefaultAmount',
        'SchoolYear'
    ];

    protected $casts = [
        'id' => 'string',
        'Payable' => 'string',
        'DefaultAmount' => 'decimal:2',
        'SchoolYear' => 'string'
    ];

    public static array $rules = [
        'Payable' => 'nullable|string|max:600',
        'DefaultAmount' => 'nullable|numeric',
        'SchoolYear' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
