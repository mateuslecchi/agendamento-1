<?php

namespace App\Traits;

use App\Domain\Enum\GroupRoles;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;

trait AuthenticatedUser
{
    protected function authIsAdmin(): bool
    {
        return GroupRoles::getByValue($this->authGroup()?->role->id) == GroupRoles::ADMIN();
    }

    protected function authGroup(): Group
    {
        return $this->authUser()->group;
    }

    protected function authUser(): User
    {
        return User::find(auth()->id());
    }

    protected function authGroupRole(): GroupRoles
    {
        return GroupRoles::getByValue($this->authGroup()?->role->id);
    }

    protected function authMember(): GroupMember
    {
        return $this->authUser()->member;
    }
}
