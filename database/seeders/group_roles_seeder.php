<?php

namespace Database\Seeders;

use App\Domain\Enum\GroupRoles;
use App\Models\GroupRole;
use Illuminate\Database\Seeder;

class group_roles_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (GroupRoles::values() as $groupRole) {
            if (empty($groupRole->getName())) {
                continue;
            }

            GroupRole::create([
                'id' => $groupRole->getValue(),
                'name' => $groupRole->getName()
            ]);
        }
    }
}
