<?php

namespace App\Repositories;

use App\Models\PayableInclusions;
use App\Repositories\BaseRepository;

class PayableInclusionsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ItemName',
        'Amount',
        'PayableId'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return PayableInclusions::class;
    }
}
