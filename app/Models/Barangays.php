<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangays extends Model
{
    public $table = 'Barangays';

    public $fillable = [
        'id',
        'Barangay',
        'TownId',
        'Notes'
    ];

    protected $casts = [
        'Barangay' => 'string',
        'TownId' => 'string',
        'Notes' => 'string'
    ];

    public static array $rules = [
        'Barangay' => 'nullable|string|max:300',
        'TownId' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
