<?php

namespace App\Repositories;

use App\Models\ClassSubjectParentAvg;
use App\Repositories\BaseRepository;

class ClassSubjectParentAvgRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ClassId',
        'ParentSubject'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ClassSubjectParentAvg::class;
    }
}
