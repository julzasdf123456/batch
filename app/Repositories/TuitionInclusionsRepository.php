<?php

namespace App\Repositories;

use App\Models\TuitionInclusions;
use App\Repositories\BaseRepository;

class TuitionInclusionsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ItemName',
        'Amount'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TuitionInclusions::class;
    }
}
