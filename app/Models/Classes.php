<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    public $table = 'Classes';

    public $fillable = [
        'id',
        'SchoolYearId',
        'ClassName',
        'Year',
        'Section',
        'Adviser'
    ];

    protected $casts = [
        'id' => 'string',
        'SchoolYearId' => 'string',
        'ClassName' => 'string',
        'Year' => 'string',
        'Section' => 'string',
        'Adviser' => 'string'
    ];

    public static array $rules = [
        'SchoolYearId' => 'nullable|string|max:50',
        'ClassName' => 'nullable|string|max:50',
        'Year' => 'nullable|string|max:50',
        'Section' => 'nullable|string|max:50',
        'Adviser' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
