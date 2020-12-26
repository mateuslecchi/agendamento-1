<?php

namespace App\Domain\Schedule\Frequency;

use App\Domain\Contracts\Frequency;

class Weekly implements Frequency
{
    public function label(): string
    {
        return 'label.frequency.weekly';
    }

    public function min(): int
    {
        return 2;
    }

    public function max(): int
    {
        return 4 * 6;
    }

    public function placeholder(): string
    {
        return 'label.week';
    }

    public function id(): int
    {
        return 2;
    }
}
