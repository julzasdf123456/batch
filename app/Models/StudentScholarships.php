<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentScholarships extends Model
{
    public $table = 'StudentScholarships';

    public $fillable = [
        'id',
        'PayableId',
        'SchoolYear',
        'ScholarshipId',
        'Amount',
        'StudentId',
        'Notes',
        'DeductMonthly'
    ];

    protected $casts = [
        'id' => 'string',
        'PayableId' => 'string',
        'SchoolYear' => 'string',
        'ScholarshipId' => 'string',
        'Amount' => 'string',
        'StudentId' => 'string',
        'Notes' => 'string',
        'DeductMonthly' => 'string',
    ];

    public static array $rules = [
        'PayableId' => 'nullable|string|max:80',
        'SchoolYear' => 'nullable|string|max:100',
        'ScholarshipId' => 'nullable|string|max:80',
        'Amount' => 'nullable|numeric',
        'StudentId' => 'nullable|string|max:80',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'DeductMonthly' => 'nullable|string',
    ];

    
}
