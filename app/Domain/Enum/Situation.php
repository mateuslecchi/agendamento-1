<?php

namespace App\Domain\Enum;

use JetBrains\PhpStorm\Pure;
use MyCLabs\Enum\Enum;

class Situation extends Enum
{
    private const CONFIRMED = 1;
    private const PENDING = 2;
    private const CANCELED = 3;
    private const ARCHIVED = 4;

    private static array $NAMES = [
        self::CONFIRMED => 'system.label.confirmed',
        self::PENDING => 'system.label.pending',
        self::CANCELED => 'system.label.canceled',
        self::ARCHIVED => 'system.label.archived',
    ];

    #[Pure] public static function CONFIRMED(): self
    {
        return new self(self::CONFIRMED);
    }

    #[Pure] public static function PENDING(): self
    {
        return new self(self::PENDING);
    }

    #[Pure] public static function CANCELED(): self
    {
        return new self(self::CANCELED);
    }

    #[Pure] public static function ARCHIVED(): self
    {
        return new self(self::ARCHIVED);
    }

    public static function getByValue(int $value): null | self
    {
        foreach (self::values() as $role) {
            if ($role->getValue() === $value) {
                return $role;
            }
        }
        return null;
    }

    #[Pure] public function getName(): string
    {
        if (!is_int($this->getValue())) {
            return '';
        }
        return self::$NAMES[$this->getValue()];
    }
}
