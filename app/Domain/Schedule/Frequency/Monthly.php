<?php

namespace App\Domain\Schedule\Frequency;

use App\Domain\Contracts\Frequency;

class Monthly implements Frequency
{

    public function label(): string
    {
        return 'label.frequency.monthly';
    }

    public function min(): int
    {
        return 2;
    }

    public function max(): int
    {
        return 6;
    }

    public function placeholder(): string
    {
        return 'label.month';
    }

    public function id(): int
    {
        return 3;
    }
}
