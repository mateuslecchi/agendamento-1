<?php

namespace App\Domain\Contracts\Schedule;

use App\Models\Block;
use App\Models\Environment;
use Illuminate\Support\Collection;

interface Retrieve
{
    public function all(): Collection;

    public function byBlock(Block $block): Collection;

    public function byEnvironment(Environment $environment): Collection;
}
