<?php

namespace App\Domain\Schedule\Frequency;

use App\Domain\Contracts\Frequency;

class Diary implements Frequency
{

    public function label(): string
    {
        return 'label.frequency.diary';
    }

    public function min(): int
    {
        return 2;
    }

    public function max(): int
    {
        return 30;
    }

    public function placeholder(): string
    {
        return 'label.day';
    }

    public function id(): int
    {
        return 1;
    }
}
