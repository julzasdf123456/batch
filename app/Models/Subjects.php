<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
    public $table = 'Subjects';

    public $fillable = [
        'id',
        'Subject',
        'Description',
        'CourseFee',
        'Teacher',
        'ParentSubject',
        'GradingType'
    ];

    protected $casts = [
        'id' => 'string',
        'Subject' => 'string',
        'Description' => 'string',
        'CourseFee' => 'string',
        'Teacher' => 'string',
        'ParentSubject' => 'string',
        'GradingType' => 'string',
    ];

    public static array $rules = [
        'id' => 'string',
        'Subject' => 'nullable|string|max:100',
        'Description' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'CourseFee' => 'nullable|string',
        'Teacher' => 'nullable|string',
        'ParentSubject' => 'nullable|string',
        'GradingType' => 'nullable|string',
    ];

    public static function parentSubjects() {
        return [
            'MAPEH' => 'MAPEH', 
            'TLE' => 'TLE', 
            'Applied Subjects' => 'Applied Subjects', 
            'Core Subjects' => 'Core Subjects', 
            'Specialized Subjects' => 'Specialized Subjects', 
            'Other Related Subjects' => 'Other Related Subjects'
        ];
    }

    public static function checkPass($grade) {
        if ($grade != null) {
            if (is_numeric($grade)) {
                if ($grade > 0 && $grade < 75) {
                    return 'FAILED';
                } elseif ($grade >= 75) {
                    return 'PASSED';
                } else {
                    return 'INC';
                }
            } else {
                if (in_array($grade, ['A', 'B', 'C'])) {
                    return 'PASSED';
                } else {
                    return 'FAILED';
                }
            }
        } else {
            return 'INC';
        }
    }

    public static function validateGrade($grade) {
        if ($grade != null) {
            if (is_numeric($grade)) {
                if ($grade > 0) {
                    return number_format($grade);
                } else {
                    return '-';
                }
            } else {
                return $grade;
            }
        } else {
            return '-';
        }
    }
}
