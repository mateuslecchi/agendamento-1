<?php

namespace Database\Seeders;

use App\Domain\Enum\GroupRoles;
use App\Domain\Enum\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class create_role_admin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $role = Role::findOrCreate(GroupRoles::ADMIN()->getName());

        $permissions = [];
        foreach (Permission::values() as $permission) {
            if ($permission == Permission::GROUP_ADMIN_EDIT() || $permission == Permission::GROUP_ADMIN_DELETE()) {
                continue;
            }
            $permissions[] = $permission->getValue();
        }

        $role->syncPermissions($permissions);
    }
}
