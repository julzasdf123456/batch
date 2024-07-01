<?php

namespace App\Repositories;

use App\Models\MiscellaneousPayables;
use App\Repositories\BaseRepository;

class MiscellaneousPayablesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'Payable',
        'DefaultAmount',
        'SchoolYear'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return MiscellaneousPayables::class;
    }
}
