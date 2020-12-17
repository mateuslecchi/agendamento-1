<?php

namespace App\Models;

use App\Domain\Enum\GroupRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property integer $id
 * @property integer $group_roles_id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property GroupRole $groupRole
 * @property Environment[] $environments
 * @property GroupMember[] $groupMembers
 */
class Group extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['group_roles_id', 'name', 'created_at', 'updated_at'];

    public static function byRole(GroupRoles $groupRole): Collection
    {
        return self::where('group_roles_id', '=', $groupRole->getValue())
            ->get();
    }

    /**
     * @return BelongsTo
     */
    public function groupRole(): BelongsTo
    {
        return $this->belongsTo(GroupRole::class, 'group_roles_id');
    }

    /**
     * @return HasMany
     */
    public function environments(): HasMany
    {
        return $this->hasMany(Environment::class, 'groups_id');
    }

    /**
     * @return HasMany
     */
    public function groupMembers(): HasMany
    {
        return $this->hasMany(GroupMember::class, 'groups_id');
    }

    public function getRoleAttribute(): GroupRole
    {
        return $this->groupRole;
    }
}
