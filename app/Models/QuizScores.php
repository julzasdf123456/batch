<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizScores extends Model
{
    public $table = 'QuizScores';

    public $fillable = [
        'id',
        'StudentId',
        'SubjectId',
        'ClassId',
        'TeacherId',
        'GradingPeriod',
        'UserId',
        'StudentScore',
        'TotalScore',
        'QuizTitle'
    ];

    protected $casts = [
        'id' => 'string',
        'StudentId' => 'string',
        'SubjectId' => 'string',
        'ClassId' => 'string',
        'TeacherId' => 'string',
        'GradingPeriod' => 'string',
        'UserId' => 'string',
        'StudentScore' => 'decimal:2',
        'TotalScore' => 'decimal:2',
        'QuizTitle' => 'string',
    ];

    public static array $rules = [
        'StudentId' => 'nullable|string|max:80',
        'SubjectId' => 'nullable|string|max:80',
        'ClassId' => 'nullable|string|max:80',
        'TeacherId' => 'nullable|string|max:80',
        'GradingPeriod' => 'nullable|string|max:50',
        'UserId' => 'nullable|string|max:50',
        'StudentScore' => 'nullable|numeric',
        'TotalScore' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'QuizTitle' => 'nullable|string',
    ];

    
}
