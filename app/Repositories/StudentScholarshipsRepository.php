<?php

namespace App\Repositories;

use App\Models\StudentScholarships;
use App\Repositories\BaseRepository;

class StudentScholarshipsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'PayableId',
        'SchoolYear',
        'ScholarshipId',
        'Amount',
        'StudentId',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return StudentScholarships::class;
    }
}
