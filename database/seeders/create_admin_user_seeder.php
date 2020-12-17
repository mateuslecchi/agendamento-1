<?php

namespace Database\Seeders;

use App\Domain\Enum\GroupRoles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class create_admin_user_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $user = User::create([
            'id' => 1,
            'name' => env('USER_ADMIN_NAME', 'administrator') ?? 'administrator',
            'email' => env('USER_ADMIN_EMAIL', 'admin@nabuk.dev') ?? 'admin@nabuk.dev',
            'password' => Hash::make(env('USER_ADMIN_PASSWD', 'fTwFkbxNK@^8PmzjMsdwDpFVkWNi46uRkESQZbD%8aDqkD&xTYqgjM9Y$rD%Cs*R') ?? 'fTwFkbxNK@^8PmzjMsdwDpFVkWNi46uRkESQZbD%8aDqkD&xTYqgjM9Y$rD%Cs*R')
        ]);

        $user->assignRole(GroupRoles::ADMIN()->getName());
    }
}
