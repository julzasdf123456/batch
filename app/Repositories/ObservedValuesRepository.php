<?php

namespace App\Repositories;

use App\Models\ObservedValues;
use App\Repositories\BaseRepository;

class ObservedValuesRepository extends BaseRepository
{
    protected $fieldSearchable = [
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

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ObservedValues::class;
    }
}
