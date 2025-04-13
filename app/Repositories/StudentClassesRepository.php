<?php

namespace App\Repositories;

use App\Models\StudentClasses;
use App\Repositories\BaseRepository;

class StudentClassesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ClassId',
        'StudentId',
        'Status',
        'EnrollmentORNumber',
        'EnrollmentORDate'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return StudentClasses::class;
    }
}
