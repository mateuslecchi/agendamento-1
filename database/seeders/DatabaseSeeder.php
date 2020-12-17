<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            group_roles_seeder::class,
            create_situation_seeder::class,

            //...
            create_permission_seeder::class,
            create_role_admin::class,
            create_role_manager::class,
            create_role_user::class,
            create_admin_group_seeder::class,
            create_admin_user_seeder::class,
            set_admin_in_group::class
        ]);
    }
}
