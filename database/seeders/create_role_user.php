<?php

namespace Database\Seeders;

use App\Domain\Enum\GroupRoles;
use App\Domain\Enum\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class create_role_user extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::findOrCreate(GroupRoles::USER()->getName());

        $role->syncPermissions([
            Permission::SCHEDULE_SHOW()->getValue(),
            Permission::SCHEDULE_CREATE()->getValue(),
            Permission::SCHEDULE_EDIT()->getValue(),

            Permission::MENU_SCHEDULES()->getValue()
        ]);
    }
}
