<?php

namespace App\Domain\Schedule\Frequency;

use App\Domain\Contracts\Frequency;

class NoRepeat implements Frequency
{
    public function label(): string
    {
        return 'label.frequency.no-repeat';
    }

    public function min(): int
    {
        return 0;
    }

    public function max(): int
    {
        return 0;
    }

    public function placeholder(): string
    {
        return '';
    }

    public function id(): int
    {
        return 0;
    }
}
