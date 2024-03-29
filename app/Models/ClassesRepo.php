<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassesRepo extends Model
{
    public $table = 'ClassesRepo';

    public $fillable = [
        'id',
        'Year',
        'ClassName',
        'Section',
        'Adviser'
    ];

    protected $casts = [
        'id' => 'string',
        'Year' => 'string',
        'ClassName' => 'string',
        'Section' => 'string',
        'Adviser' => 'string',
    ];

    public static array $rules = [
        'Year' => 'nullable|string|max:50',
        'ClassName' => 'nullable|string|max:50',
        'Section' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Adviser' => 'nullable|string',
    ];

    
}
