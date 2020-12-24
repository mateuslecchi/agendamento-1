<?php

namespace App\Traits;

use App\Models\Group;
use App\Models\GroupMember;
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

    public static function personalGroup(): Group
    {
        $group = Make::group([
            Group::NAME => 'label.personal-group'
        ]);
        $group->id = -1;
        return $group;
    }
}
