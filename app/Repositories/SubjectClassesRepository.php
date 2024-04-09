<?php

namespace App\Repositories;

use App\Models\SubjectClasses;
use App\Repositories\BaseRepository;

class SubjectClassesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'SubjectId',
        'ClassRepoId',
        'UserId'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return SubjectClasses::class;
    }
}
