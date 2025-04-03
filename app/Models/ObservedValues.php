<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObservedValues extends Model
{
    public $table = 'ObservedValues';

    protected $connection = 'sqlsrv';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'StudentId',
        'ClassId',
        'MakaDiyosOne',
        'MakaDiyosTwo',
        'MakaTaoOne',
        'MakaTaoTwo',
        'MakaKalikasan',
        'MakaBansaOne',
        'MakaBansaTwo',
        'IndustryOne',
        'IndustryTwo'
    ];

    protected $casts = [
        'StudentId' => 'string',
        'ClassId' => 'string',
        'MakaDiyosOne' => 'string',
        'MakaDiyosTwo' => 'string',
        'MakaTaoOne' => 'string',
        'MakaTaoTwo' => 'string',
        'MakaKalikasan' => 'string',
        'MakaBansaOne' => 'string',
        'MakaBansaTwo' => 'string',
        'IndustryOne' => 'string',
        'IndustryTwo' => 'string'
    ];

    public static array $rules = [
        'StudentId' => 'nullable|string|max:50',
        'ClassId' => 'nullable|string|max:50',
        'MakaDiyosOne' => 'nullable|string|max:50',
        'MakaDiyosTwo' => 'nullable|string|max:50',
        'MakaTaoOne' => 'nullable|string|max:50',
        'MakaTaoTwo' => 'nullable|string|max:50',
        'MakaKalikasan' => 'nullable|string|max:50',
        'MakaBansaOne' => 'nullable|string|max:50',
        'MakaBansaTwo' => 'nullable|string|max:50',
        'IndustryOne' => 'nullable|string|max:50',
        'IndustryTwo' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
