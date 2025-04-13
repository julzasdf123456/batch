<?php

namespace App\Repositories;

use App\Models\Classes;
use App\Repositories\BaseRepository;

class ClassesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'SchoolYearId',
        'ClassName',
        'Year',
        'Section',
        'Adviser'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Classes::class;
    }
}
