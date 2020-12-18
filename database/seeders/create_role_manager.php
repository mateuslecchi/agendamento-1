<?php
/** @noinspection PhpUndefinedMethodInspection */

namespace Database\Seeders;

use App\Domain\Enum\GroupRoles;
use App\Domain\Enum\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class create_role_manager extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $role = Role::findOrCreate(GroupRoles::MANAGER()->getName());

        $role->syncPermissions([
            Permission::ENVIRONMENT_SHOW()->getValue(),
            Permission::ENVIRONMENT_CREATE()->getValue(),
            Permission::ENVIRONMENT_EDIT()->getValue(),
            Permission::ENVIRONMENT_DELETE()->getValue(),

            Permission::SCHEDULE_SHOW()->getValue(),
            Permission::SCHEDULE_CREATE()->getValue(),
            Permission::SCHEDULE_EDIT()->getValue(),
            Permission::SCHEDULE_SET_GROUP()->getValue(),
            Permission::SCHEDULE_SET_FREQUENCY()->getValue(),

            Permission::MENU_DASHBOARD()->getValue(),
            Permission::MENU_ENVIRONMENTS()->getValue(),
            Permission::MENU_SCHEDULES()->getValue()
        ]);
    }
}
