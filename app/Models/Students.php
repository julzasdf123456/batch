<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    public $table = 'Students';

    public $fillable = [
        'id',
        'FirstName',
        'MiddleName',
        'LastName',
        'Suffix',
        'Birthdate',
        'Gender',
        'Sitio',
        'Barangay',
        'Town',
        'ContactNumber'
    ];

    protected $casts = [
        'id' => 'string',
        'FirstName' => 'string',
        'MiddleName' => 'string',
        'LastName' => 'string',
        'Suffix' => 'string',
        'Birthdate' => 'date',
        'Gender' => 'string',
        'Sitio' => 'string',
        'Barangay' => 'string',
        'Town' => 'string',
        'ContactNumber' => 'string'
    ];

    public static array $rules = [
        'FirstName' => 'nullable|string|max:60',
        'MiddleName' => 'nullable|string|max:60',
        'LastName' => 'nullable|string|max:60',
        'Suffix' => 'nullable|string|max:60',
        'Birthdate' => 'nullable',
        'Gender' => 'nullable|string|max:50',
        'Sitio' => 'nullable|string|max:60',
        'Barangay' => 'nullable|string|max:50',
        'Town' => 'nullable|string|max:50',
        'ContactNumber' => 'nullable|string|max:90',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
