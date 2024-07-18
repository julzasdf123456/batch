<?php

namespace App\Repositories;

use App\Models\SmsMessages;
use App\Repositories\BaseRepository;

class SmsMessagesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'ContactNumber',
        'Message',
        'AIFacilitator',
        'Source',
        'Priority',
        'SmsSent'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return SmsMessages::class;
    }
}
