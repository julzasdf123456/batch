<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentClasses extends Model
{
    public $table = 'StudentClasses';

    public $fillable = [
        'id',
        'ClassId',
        'StudentId',
        'Status',
        'EnrollmentORNumber',
        'EnrollmentORDate'
    ];

    protected $casts = [
        'id' => 'string',
        'ClassId' => 'string',
        'StudentId' => 'string',
        'Status' => 'string',
        'EnrollmentORNumber' => 'string',
        'EnrollmentORDate' => 'date'
    ];

    public static array $rules = [
        'ClassId' => 'nullable|string|max:80',
        'StudentId' => 'nullable|string|max:50',
        'Status' => 'nullable|string|max:50',
        'EnrollmentORNumber' => 'nullable|string|max:50',
        'EnrollmentORDate' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
