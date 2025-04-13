<?php

namespace App\Repositories;

use App\Models\StudentSubjects;
use App\Repositories\BaseRepository;

class StudentSubjectsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'StudentId',
        'SubjectId',
        'ClassId',
        'TeacherId',
        'FirstGradingGrade',
        'SecondGradingGrade',
        'ThirdGradingGrade',
        'FourthGradingGrade',
        'AverageGrade',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return StudentSubjects::class;
    }
}
