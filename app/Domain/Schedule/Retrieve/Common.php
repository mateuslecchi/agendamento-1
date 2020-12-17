<?php

namespace App\Domain\Schedule\Retrieve;

use App\Domain\Contracts\Schedule\Retrieve;
use App\Domain\Enum\Situation;
use App\Models\Block;
use App\Models\Environment;
use App\Models\Schedule;
use App\Traits\AuthenticatedUser;
use Illuminate\Support\Collection;

class Common implements Retrieve
{
    use AuthenticatedUser;

    public function all(): Collection
    {
        return Schedule::afterOrEqualDateCollection(
            query: Schedule::byGroupBuilder($this->authGroup()),
            dateTime: now()
        )->reject(function (Schedule $schedule) {
            return match ($schedule->situations_id) {
                Situation::CANCELED()->getValue(),
                Situation::ARCHIVED()->getValue() => true,
                default => false
            };
        });
    }

    public function byBlock(Block $block): Collection
    {
        return Schedule::afterOrEqualDateCollection(
            query: Schedule::byGroupAndBlockBuilder($this->authGroup(), $block),
            dateTime: now()
        )->reject(function (Schedule $schedule) {
            return match ($schedule->situations_id) {
                Situation::CANCELED()->getValue(),
                Situation::ARCHIVED()->getValue() => true,
                default => false
            };
        });
    }

    public function byEnvironment(Environment $environment): Collection
    {
        return Schedule::afterOrEqualDateCollection(
            query: Schedule::byEnvironmentBuilder($environment),
            dateTime: now()
        )->reject(function (Schedule $schedule) {
            return match ($schedule->situations_id) {
                Situation::CANCELED()->getValue(),
                Situation::ARCHIVED()->getValue() => true,
                default => false
            };
        });
    }
}
