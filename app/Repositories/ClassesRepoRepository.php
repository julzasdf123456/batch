<?php

namespace App\Repositories;

use App\Models\ClassesRepo;
use App\Repositories\BaseRepository;

class ClassesRepoRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'Year',
        'ClassName',
        'Section'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ClassesRepo::class;
    }
}
