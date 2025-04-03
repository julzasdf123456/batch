<?php

namespace App\Repositories;

use App\Models\ObservedValues;
use App\Repositories\BaseRepository;

class ObservedValuesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'StudentId',
        'ClassId',
        'ObservedValue',
        'FirstQuarter',
        'SecondQuarter',
        'ThirdQuarter',
        'FourthQuarter',
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
