<?php

namespace App\Domain;

use App\Domain\Enum\GroupRoles;
use App\Domain\Enum\Permission;
use App\Traits\AuthorizesRoleOrPermission;

class Policy
{
    use AuthorizesRoleOrPermission;

    public static function users_edit_mount(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            Permission::GROUP_SHOW()->getValue()
        ]);
    }

    public static function groups_show_mount(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            Permission::GROUP_SHOW()->getValue()
        ]);
    }

    public static function groups_create_mount(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            Permission::GROUP_CREATE()->getValue()
        ]);
    }

    public static function groups_create_save(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            Permission::GROUP_CREATE()->getValue()
        ]);
    }

    public static function groups_edit_mount(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            Permission::GROUP_EDIT()->getValue()
        ]);
    }

    public static function groups_edit_load(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            Permission::GROUP_EDIT()->getValue()
        ]);
    }

    public static function groups_edit_admin(): void
    {
        self::authRoleOrPermission([
            Permission::GROUP_ADMIN_EDIT()->getValue()
        ]);
    }

    public static function groups_delete_mount(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            Permission::GROUP_DELETE()->getValue()
        ]);
    }

    public static function groups_delete_load(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            Permission::GROUP_DELETE()->getValue()
        ]);
    }

    public static function groups_delete_admin(): void
    {
        self::authRoleOrPermission([
            Permission::GROUP_ADMIN_DELETE()->getValue()
        ]);
    }

    public static function groups_delete_save(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            Permission::GROUP_DELETE()->getValue()
        ]);
    }

    /** Blocks */
    public static function blocks_show_mount(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            Permission::BLOCK_SHOW()->getValue()
        ]);
    }

    public static function blocks_create_mount(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            Permission::BLOCK_CREATE()->getValue()
        ]);
    }

    public static function blocks_create_save(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            Permission::BLOCK_CREATE()->getValue()
        ]);
    }

    public static function blocks_edit_mount(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            Permission::BLOCK_EDIT()->getValue()
        ]);
    }

    public static function blocks_edit_load(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            Permission::BLOCK_EDIT()->getValue()
        ]);
    }

    public static function blocks_delete_mount(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            Permission::BLOCK_DELETE()->getValue()
        ]);
    }

    public static function blocks_delete_load(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            Permission::BLOCK_DELETE()->getValue()
        ]);
    }

    public static function blocks_delete_delete(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            Permission::BLOCK_DELETE()->getValue()
        ]);
    }

    /** Environment */
    public static function environments_show_mount(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            GroupRoles::MANAGER()->getName(),
            Permission::ENVIRONMENT_SHOW()->getValue()
        ]);
    }

    public static function environments_create_mount(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            GroupRoles::MANAGER()->getName(),
            Permission::ENVIRONMENT_CREATE()->getValue()
        ]);
    }

    public static function environments_create_save(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            GroupRoles::MANAGER()->getName(),
            Permission::ENVIRONMENT_CREATE()->getValue()
        ]);
    }

    public static function environments_edit_mount(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            GroupRoles::MANAGER()->getName(),
            Permission::ENVIRONMENT_EDIT()->getValue()
        ]);
    }

    public static function environments_edit_load(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            GroupRoles::MANAGER()->getName(),
            Permission::ENVIRONMENT_EDIT()->getValue()
        ]);
    }

    public static function environments_delete_mount(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            GroupRoles::MANAGER()->getName(),
            Permission::ENVIRONMENT_DELETE()->getValue()
        ]);
    }

    public static function environments_delete_load(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            GroupRoles::MANAGER()->getName(),
            Permission::ENVIRONMENT_DELETE()->getValue()
        ]);
    }

    public static function environments_delete_delete(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            GroupRoles::MANAGER()->getName(),
            Permission::ENVIRONMENT_DELETE()->getValue()
        ]);
    }

    /** Schedule */
    public static function schedule_show_mount(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            GroupRoles::MANAGER()->getName(),
            GroupRoles::USER()->getName(),
            Permission::SCHEDULE_SHOW()->getValue()
        ]);
    }

    public static function schedule_create_mount(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            GroupRoles::MANAGER()->getName(),
            GroupRoles::USER()->getName(),
            Permission::SCHEDULE_CREATE()->getValue()
        ]);
    }

    public static function schedule_create_load(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            GroupRoles::MANAGER()->getName(),
            GroupRoles::USER()->getName(),
            Permission::SCHEDULE_CREATE()->getValue()
        ]);
    }

    public static function schedule_create_save(): void
    {
        self::authRoleOrPermission([
            GroupRoles::ADMIN()->getName(),
            GroupRoles::MANAGER()->getName(),
            GroupRoles::USER()->getName(),
            Permission::SCHEDULE_CREATE()->getValue()
        ]);
    }
}
