<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scholarships extends Model
{
    public $table = 'Scholarships';

    public $fillable = [
        'id',
        'Scholarship',
        'Notes',
        'Percentage',
        'Amount'
    ];

    protected $casts = [
        'id' => 'string',
        'Scholarship' => 'string',
        'Notes' => 'string',
        'Percentage' => 'string',
        'Amount' => 'string'
    ];

    public static array $rules = [
        'Scholarship' => 'nullable|string|max:500',
        'Notes' => 'nullable|string|max:1200',
        'Percentage' => 'nullable|string',
        'Amount' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
