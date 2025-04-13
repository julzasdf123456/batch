<?php

namespace App\Repositories;

use App\Models\QuizScores;
use App\Repositories\BaseRepository;

class QuizScoresRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'StudentId',
        'SubjectId',
        'ClassId',
        'TeacherId',
        'GradingPeriod',
        'UserId',
        'StudentScore',
        'TotalScore'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return QuizScores::class;
    }
}
