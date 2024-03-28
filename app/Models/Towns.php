<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Towns extends Model
{
    public $table = 'Towns';

    public $fillable = [
        'id',
        'Town',
        'District',
        'Station'
    ];

    protected $casts = [
        'id' => 'string',
        'Town' => 'string',
        'District' => 'string',
        'Station' => 'string'
    ];

    public static array $rules = [
        'Town' => 'nullable|string|max:500',
        'District' => 'nullable|string|max:50',
        'Station' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable|string|max:50'
    ];

    
}
