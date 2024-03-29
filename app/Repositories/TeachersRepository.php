<?php

namespace App\Repositories;

use App\Models\Teachers;
use App\Repositories\BaseRepository;

class TeachersRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'FullName',
        'Designation',
        'SubjectExpertise',
        'Department',
        'Status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Teachers::class;
    }
}
