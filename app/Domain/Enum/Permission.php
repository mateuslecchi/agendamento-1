<?php

namespace App\Domain\Enum;

use JetBrains\PhpStorm\Pure;
use MyCLabs\Enum\Enum;

class Permission extends Enum
{
    private const GROUP_SHOW = 'group::show';
    private const GROUP_CREATE = 'group::create';
    private const GROUP_EDIT = 'group::edit';
    private const GROUP_DELETE = 'group::delete';
    private const GROUP_ADMIN_EDIT = 'group::admin::edit';
    private const GROUP_ADMIN_DELETE = 'group::admin::delete';

    private const BLOCK_SHOW = 'block::show';
    private const BLOCK_CREATE = 'block::create';
    private const BLOCK_EDIT = 'block::edit';
    private const BLOCK_DELETE = 'block::delete';

    private const ENVIRONMENT_SHOW = 'environment::show';
    private const ENVIRONMENT_CREATE = 'environment::create';
    private const ENVIRONMENT_EDIT = 'environment::edit';
    private const ENVIRONMENT_DELETE = 'environment::delete';
    private const ENVIRONMENT_SET_GROUP = 'environment::set::group';

    private const SCHEDULE_SHOW = 'schedule::show';
    private const SCHEDULE_CREATE = 'schedule::create';
    private const SCHEDULE_EDIT = 'schedule::edit';
    private const SCHEDULE_DELETE = 'schedule::delete';

    private const SCHEDULE_SET_GROUP = 'schedule::set::group';
    private const SCHEDULE_SET_FREQUENCY = 'schedule::set::frequency';

    private const MENU_DASHBOARD = 'menu::dashboard';
    private const MENU_GROUPS = 'menu::groups';
    private const MENU_USERS = 'menu::users';
    private const MENU_BLOCKS = 'menu::blocks';
    private const MENU_ENVIRONMENTS = 'menu::environments';
    private const MENU_SCHEDULES = 'menu::schedules';

    #[Pure] public static function GROUP_SHOW(): self
    {
        return new self(self::GROUP_SHOW);
    }

    #[Pure] public static function GROUP_CREATE(): self
    {
        return new self(self::GROUP_CREATE);
    }

    #[Pure] public static function GROUP_EDIT(): self
    {
        return new self(self::GROUP_EDIT);
    }

    #[Pure] public static function GROUP_DELETE(): self
    {
        return new self(self::GROUP_DELETE);
    }

    #[Pure] public static function GROUP_ADMIN_EDIT(): self
    {
        return new self(self::GROUP_ADMIN_EDIT);
    }

    #[Pure] public static function GROUP_ADMIN_DELETE(): self
    {
        return new self(self::GROUP_ADMIN_DELETE);
    }

    /** Blocks */
    #[Pure] public static function BLOCK_SHOW(): self
    {
        return new self(self::BLOCK_SHOW);
    }

    #[Pure] public static function BLOCK_CREATE(): self
    {
        return new self(self::BLOCK_CREATE);
    }

    #[Pure] public static function BLOCK_EDIT(): self
    {
        return new self(self::BLOCK_EDIT);
    }

    #[Pure] public static function BLOCK_DELETE(): self
    {
        return new self(self::BLOCK_DELETE);
    }

    /** Environment */
    #[Pure] public static function ENVIRONMENT_SHOW(): self
    {
        return new self(self::ENVIRONMENT_SHOW);
    }

    #[Pure] public static function ENVIRONMENT_CREATE(): self
    {
        return new self(self::ENVIRONMENT_CREATE);
    }

    #[Pure] public static function ENVIRONMENT_EDIT(): self
    {
        return new self(self::ENVIRONMENT_EDIT);
    }

    #[Pure] public static function ENVIRONMENT_DELETE(): self
    {
        return new self(self::ENVIRONMENT_DELETE);
    }

    #[Pure] public static function ENVIRONMENT_SET_GROUP(): self
    {
        return new self(self::ENVIRONMENT_SET_GROUP);
    }

    /** Schedule */
    #[Pure] public static function SCHEDULE_SHOW(): self
    {
        return new self(self::SCHEDULE_SHOW);
    }

    #[Pure] public static function SCHEDULE_CREATE(): self
    {
        return new self(self::SCHEDULE_CREATE);
    }

    #[Pure] public static function SCHEDULE_EDIT(): self
    {
        return new self(self::SCHEDULE_EDIT);
    }

    #[Pure] public static function SCHEDULE_DELETE(): self
    {
        return new self(self::SCHEDULE_DELETE);
    }

    #[Pure] public static function MENU_DASHBOARD(): self
    {
        return new self(self::MENU_DASHBOARD);
    }

    #[Pure] public static function SCHEDULE_SET_GROUP(): self
    {
        return new self(self::SCHEDULE_SET_GROUP);
    }

    #[Pure] public static function SCHEDULE_SET_FREQUENCY(): self
    {
        return new self(self::SCHEDULE_SET_FREQUENCY);
    }

    /** Menu */
    #[Pure] public static function MENU_GROUPS(): self
    {
        return new self(self::MENU_GROUPS);
    }

    #[Pure] public static function MENU_USERS(): self
    {
        return new self(self::MENU_USERS);
    }

    #[Pure] public static function MENU_BLOCKS(): self
    {
        return new self(self::MENU_BLOCKS);
    }

    #[Pure] public static function MENU_ENVIRONMENTS(): self
    {
        return new self(self::MENU_ENVIRONMENTS);
    }

    #[Pure] public static function MENU_SCHEDULES(): self
    {
        return new self(self::MENU_SCHEDULES);
    }
}
