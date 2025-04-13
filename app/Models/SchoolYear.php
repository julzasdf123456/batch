<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    public $table = 'SchoolYear';

    public $fillable = [
        'id',
        'SchoolYear',
        'MonthStart'
    ];

    protected $casts = [
        'id' => 'string',
        'SchoolYear' => 'string',
        'MonthStart' => 'string',
    ];

    public static array $rules = [
        'SchoolYear' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'MonthStart' => 'nullable|string',
    ];

    
}
