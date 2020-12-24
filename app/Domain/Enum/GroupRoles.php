<?php

namespace App\Domain\Enum;

use JetBrains\PhpStorm\Pure;
use MyCLabs\Enum\Enum;

class GroupRoles extends Enum
{
    private const ADMIN = 1;
    private const MANAGER = 2;
    private const USER = 3;

    private static array $NAMES = [
        self::ADMIN => 'system.label.admin',
        self::MANAGER => 'system.label.manager',
        self::USER => 'system.label.user',
    ];

    #[Pure] public static function ADMIN(): self
    {
        return new self(self::ADMIN);
    }

    #[Pure] public static function MANAGER(): self
    {
        return new self(self::MANAGER);
    }

    #[Pure] public static function USER(): self
    {
        return new self(self::USER);
    }

    public static function getByValue(int $value, self|null $default = null): self
    {
        foreach (self::values() as $role) {
            if ($role->getValue() === $value) {
                return $role;
            }
        }
        if (is_null($default)) {
            return self::USER();
        }
        return $default;
    }

    #[Pure] public function getName(): string
    {
        if (!is_int($this->getValue())) {
            return '';
        }
        return self::$NAMES[$this->getValue()];
    }
}
