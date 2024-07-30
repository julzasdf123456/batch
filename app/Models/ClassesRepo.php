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
        'Adviser',
        'BaseTuitionFee',
        'Strand',
        'Semester',
        'BaseTuitionFeePublic'
    ];

    protected $casts = [
        'id' => 'string',
        'Year' => 'string',
        'ClassName' => 'string',
        'Section' => 'string',
        'Adviser' => 'string',
        'BaseTuitionFee' => 'string',
        'Strand' => 'string',
        'Semester' => 'string',
        'BaseTuitionFeePublic' => 'string',
    ];

    public static array $rules = [
        'Year' => 'nullable|string|max:50',
        'ClassName' => 'nullable|string|max:50',
        'Section' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Adviser' => 'nullable|string',
        'BaseTuitionFee' => 'nullable|string',
        'Strand' => 'nullable|string',
        'Semester' => 'nullable|string',
        'BaseTuitionFeePublic' => 'nullable|string',
    ];

    
}
