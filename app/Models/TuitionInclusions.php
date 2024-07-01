<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TuitionInclusions extends Model
{
    public $table = 'TuitionInclusions';

    public $fillable = [
        'id',
        'ItemName',
        'Amount',
        'ClassRepoId',
    ];

    protected $casts = [
        'id' => 'string',
        'ItemName' => 'string',
        'Amount' => 'string',
        'ClassRepoId' => 'string',
    ];

    public static array $rules = [
        'ItemName' => 'nullable|string|max:1000',
        'Amount' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'ClassRepoId' => 'nullable|string',
    ];

    
}
