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
        'ParentSubject'
    ];

    protected $casts = [
        'id' => 'string',
        'Subject' => 'string',
        'Description' => 'string',
        'CourseFee' => 'string',
        'Teacher' => 'string',
        'ParentSubject' => 'string',
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
}
