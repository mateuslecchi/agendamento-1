<?php

namespace App\Traits;

use App\Models\Block;
use App\Models\Environment;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Schedule;
use App\Models\User;

trait Make
{
    public static function user(array $attributes = []): User
    {
        return User::make($attributes);
    }

    public static function group(array $attributes = []): Group
    {
        return Group::make($attributes);
    }

    public static function groupMember(array $attributes = []): GroupMember
    {
        return GroupMember::make($attributes);
    }

    public static function environment(array $attributes = []): Environment
    {
        return Environment::make($attributes);
    }

    public static function schedule(array $attributes = []): Schedule
    {
        return Schedule::make($attributes);
    }

    public static function block(array $attributes = []): Block
    {
        return Block::make($attributes);
    }

    public static function fakePersonalGroup(): Group
    {
        $group = Make::group([
            Group::NAME => 'label.personal-group'
        ]);
        $group->id = -1;
        return $group;
    }
}
