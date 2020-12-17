<?php

namespace App\Domain\Enum;

use JetBrains\PhpStorm\Pure;
use MyCLabs\Enum\Enum;

class AdminGroup extends Enum
{
    private const ID = 1;
    private const NAME = 'system.label.group.admin';
    private const ROLE_ID = 1;

    #[Pure] public static function ID(): self
    {
        return new self(self::ID);
    }

    #[Pure] public static function NAME(): self
    {
        return new self(self::NAME);
    }

    #[Pure] public static function ROLE_ID(): self
    {
        return new self(self::ROLE_ID);
    }
}
