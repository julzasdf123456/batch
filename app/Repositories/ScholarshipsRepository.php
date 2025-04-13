<?php

namespace App\Repositories;

use App\Models\Scholarships;
use App\Repositories\BaseRepository;

class ScholarshipsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'Scholarship',
        'Notes',
        'Percentage',
        'Amount'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Scholarships::class;
    }
}
