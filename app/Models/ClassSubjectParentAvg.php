<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSubjectParentAvg extends Model
{
    public $table = 'ClassSubjectParentAvg';

    public $fillable = [
        'id',
        'ClassId',
        'ParentSubject'
    ];

    protected $casts = [
        'id' => 'string',
        'ClassId' => 'string',
        'ParentSubject' => 'string'
    ];

    public static array $rules = [
        'ClassId' => 'nullable|string|max:50',
        'ParentSubject' => 'nullable|string|max:250',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
