<?php

namespace App\Traits;

use App\Domain\Enum\GroupRoles;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Support\Str;
use TypeError;

trait AuthenticatedUser
{
    /** @noinspection TypeUnsafeComparisonInspection
     * @noinspection PhpNonStrictObjectEqualityInspection
     */
    protected function authIsAdmin(): bool
    {
        return GroupRoles::getByValue($this->authGroup()?->role->id) == GroupRoles::ADMIN();
    }

    protected function authGroup(): Group
    {
        try {
            return $this->authUser()->group;
        } catch (TypeError)
        {
            $this->authUser()->syncRoles(GroupRoles::USER()->getName());

            $group = Group::create([
                'name' => Str::ucfirst(__("label.custom.violation-of-group-integrity", [
                    'text' => $this->authUser()->name
                ])),
                'group_roles_id' => GroupRoles::USER()->getValue()
            ]);

            GroupMember::create([
                'groups_id' => $group->id,
                'users_id' => $this->authUser()->id,
            ]);

            return $this->authGroup();
        }
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
