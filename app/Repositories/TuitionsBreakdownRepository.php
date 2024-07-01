<?php

namespace App\Repositories;

use App\Models\TuitionsBreakdown;
use App\Repositories\BaseRepository;

class TuitionsBreakdownRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ForMonth',
        'PayableId',
        'AmountPayable',
        'AmountPaid',
        'Balance'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TuitionsBreakdown::class;
    }
}
