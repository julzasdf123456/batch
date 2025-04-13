<?php

namespace App\Repositories;

use App\Models\TransactionDetails;
use App\Repositories\BaseRepository;

class TransactionDetailsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'TransactionsId',
        'Particulars',
        'AccountNumber',
        'Amount'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return TransactionDetails::class;
    }
}
