<?php

namespace Database\Seeders;

use App\Domain\Enum\AdminGroup;
use App\Models\GroupMember;
use Illuminate\Database\Seeder;

class set_admin_in_group extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GroupMember::create([
            'id' => 1,
            'groups_id' => AdminGroup::ID(),
            'users_id' => 1
        ]);
    }
}
