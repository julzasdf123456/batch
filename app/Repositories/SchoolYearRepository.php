<?php

namespace App\Repositories;

use App\Models\SchoolYear;
use App\Repositories\BaseRepository;

class SchoolYearRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'SchoolYear'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return SchoolYear::class;
    }
}
