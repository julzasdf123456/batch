<?php

namespace App\Repositories;

use App\Models\Barangays;
use App\Repositories\BaseRepository;

class BarangaysRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'id',
        'Barangay',
        'TownId',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Barangays::class;
    }
}
