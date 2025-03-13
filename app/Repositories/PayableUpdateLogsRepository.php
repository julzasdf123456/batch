<?php

namespace App\Repositories;

use App\Models\PayableUpdateLogs;
use App\Repositories\BaseRepository;

class PayableUpdateLogsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'PayableId',
        'UserId',
        'OGTotalPayable',
        'OGPaidAmount',
        'OGBalance',
        'NewTotalPayable',
        'NewPaidAmount',
        'NewBalance'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return PayableUpdateLogs::class;
    }
}
