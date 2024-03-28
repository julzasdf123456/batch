<?php

namespace App\Repositories;

use App\Models\Towns;
use App\Repositories\BaseRepository;

class TownsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'id',
        'Town',
        'District',
        'Station'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Towns::class;
    }
}
