<?php

namespace Database\Seeders;

use App\Domain\Enum\AdminGroup;
use App\Models\Group;
use Illuminate\Database\Seeder;

class create_admin_group_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::create([
            'id' => AdminGroup::ID(),
            'name' => AdminGroup::NAME(),
            'group_roles_id' => AdminGroup::ROLE_ID()
        ]);
    }
}
