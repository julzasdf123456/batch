<?php

namespace App\Repositories;

use App\Models\Transactions;
use App\Repositories\BaseRepository;

class TransactionsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'PayablesId',
        'StudentId',
        'PaymentFor',
        'ModeOfPayment',
        'ORNumber',
        'ORDate',
        'CashAmount',
        'CheckAmount',
        'DigitalPaymentAmount',
        'TotalAmountPaid',
        'Notes'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Transactions::class;
    }
}
