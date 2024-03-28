<?php

namespace App\Repositories;

use App\Models\Students;
use App\Repositories\BaseRepository;

class StudentsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'FirstName',
        'MiddleName',
        'LastName',
        'Suffix',
        'Birthdate',
        'Gender',
        'Sitio',
        'Barangay',
        'Town',
        'ContactNumber'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Students::class;
    }
}
