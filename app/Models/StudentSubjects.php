<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSubjects extends Model
{
    public $table = 'StudentSubjects';

    public $fillable = [
        'id',
        'StudentId',
        'SubjectId',
        'ClassId',
        'TeacherId',
        'FirstGradingGrade',
        'SecondGradingGrade',
        'ThirdGradingGrade',
        'FourthGradingGrade',
        'AverageGrade',
        'Notes',
        'Visibility'
    ];

    protected $casts = [
        'id' => 'string',
        'StudentId' => 'string',
        'SubjectId' => 'string',
        'ClassId' => 'string',
        'TeacherId' => 'string',
        'FirstGradingGrade' => 'decimal:2',
        'SecondGradingGrade' => 'decimal:2',
        'ThirdGradingGrade' => 'decimal:2',
        'FourthGradingGrade' => 'decimal:2',
        'AverageGrade' => 'decimal:2',
        'Notes' => 'string',
        'Visibility' => 'string',
    ];

    public static array $rules = [
        'id' => 'string',
        'StudentId' => 'nullable|string|max:80',
        'SubjectId' => 'nullable|string|max:80',
        'ClassId' => 'nullable|string|max:80',
        'TeacherId' => 'nullable|string|max:80',
        'FirstGradingGrade' => 'nullable|numeric',
        'SecondGradingGrade' => 'nullable|numeric',
        'ThirdGradingGrade' => 'nullable|numeric',
        'FourthGradingGrade' => 'nullable|numeric',
        'AverageGrade' => 'nullable|numeric',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Visibility' => 'nullable|string',
    ];

    
}
