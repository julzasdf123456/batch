<?php

namespace App\Repositories;

use App\Models\Payables;
use App\Repositories\BaseRepository;

class PayablesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'StudentId',
        'PaymentFor',
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
        return Payables::class;
    }
}
