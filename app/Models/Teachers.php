<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teachers extends Model
{
    public $table = 'Teachers';

    public $fillable = [
        'id',
        'FullName',
        'Designation',
        'SubjectExpertise',
        'Department',
        'Status'
    ];

    protected $casts = [
        'id' => 'string',
        'FullName' => 'string',
        'Designation' => 'string',
        'SubjectExpertise' => 'string',
        'Department' => 'string',
        'Status' => 'string'
    ];

    public static array $rules = [
        'FullName' => 'nullable|string|max:500',
        'Designation' => 'nullable|string|max:50',
        'SubjectExpertise' => 'nullable|string|max:500',
        'Department' => 'nullable|string|max:50',
        'Status' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
