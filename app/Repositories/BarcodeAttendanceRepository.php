<?php

namespace App\Repositories;

use App\Models\BarcodeAttendance;
use App\Repositories\BaseRepository;

class BarcodeAttendanceRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'StudentId',
        'PunchType',
        'BarcodeId',
        'SmsSent'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return BarcodeAttendance::class;
    }
}
