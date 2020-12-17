<?php

namespace Database\Seeders;

use App\Domain\Enum\Permission as PermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class create_permission_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        foreach (PermissionEnum::values() as $permission) {
            Permission::findOrCreate($permission->getValue());
        }
    }
}
