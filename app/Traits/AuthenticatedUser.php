<?php

namespace App\Traits;

use App\Domain\Enum\GroupRoles;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
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
        } catch (TypeError) {
            $this->ensureIntegrity_createPersonalGroup();
            return $this->authGroup();
        }
    }

    protected function ensureIntegrity_createPersonalGroup(): void
    {
        $this->authUser()->syncRoles(GroupRoles::USER()->getName());

        $group = Make::group([
            Group::NAME => Fmt::text($this->authUser()->name),
            Group::GROUP_ROLE_ID => GroupRoles::USER()->getValue(),
            Group::PERSONAL_GROUP => true
        ]);

        $group->save();

        Make::groupMember([
            'groups_id' => $group->id,
            'users_id' => $this->authUser()->id
        ])->save();
    }

    /** @noinspection PhpInconsistentReturnPointsInspection */
    protected function authUser(): User
    {
        try{
            return User::find(auth()->id());
        } catch (TypeError){
            auth()->logout();
        }
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
