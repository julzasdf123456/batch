<?php

namespace App\Repositories;

use App\Models\Subjects;
use App\Repositories\BaseRepository;

class SubjectsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'Subject',
        'Description'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Subjects::class;
    }
}
