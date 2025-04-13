<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectClasses extends Model
{
    public $table = 'SubjectClasses';

    public $fillable = [
        'id',
        'SubjectId',
        'ClassRepoId',
        'UserId',
        'Heirarchy'
    ];

    protected $casts = [
        'id' => 'string',
        'SubjectId' => 'string',
        'ClassRepoId' => 'string',
        'UserId' => 'string',
        'Heirarchy' => 'string',
    ];

    public static array $rules = [
        'id' => 'string',
        'SubjectId' => 'nullable|string|max:80',
        'ClassRepoId' => 'nullable|string|max:80',
        'UserId' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Heirarchy' => 'nullable|string',
    ];

    
}
