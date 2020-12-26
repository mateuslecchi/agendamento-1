<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class EndTimeBeforeStartTime implements Rule
{
    protected Carbon $startTime;
    protected Carbon $endTime;

    public function __construct(string $startTime, string $endTime)
    {
        $this->startTime = Carbon::parse($startTime);
        $this->endTime = Carbon::parse($endTime);
    }

    public function passes($attribute, $value): bool
    {
        return !$this->endTime->isBefore($this->startTime);
    }

    public function message(): string
    {
        return 'Não falei? nossa maquina do tempo está quebrada!';
    }
}
