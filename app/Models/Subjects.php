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
        'CourseFee'
    ];

    protected $casts = [
        'id' => 'string',
        'Subject' => 'string',
        'Description' => 'string',
        'CourseFee' => 'string'
    ];

    public static array $rules = [
        'id' => 'string',
        'Subject' => 'nullable|string|max:100',
        'Description' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'CourseFee' => 'nullable|string'
    ];

    
}
