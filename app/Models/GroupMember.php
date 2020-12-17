<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $groups_id
 * @property integer $users_id
 * @property Group $group
 * @property User $user
 * @property Schedule[] $schedules
 */
class GroupMember extends Model
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
    protected $fillable = ['groups_id', 'users_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('App\Models\Group', 'groups_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'users_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedules()
    {
        return $this->hasMany('App\Models\Schedule', 'group_members_id');
    }

    public static function findByUser(int $id): null | GroupMember
    {
        return self::where('users_id', '=', $id)?->first();
    }

    public static function findByGroup(Group $group): Collection
    {
        return self::where('groups_id', '=', $group->id)->get() ?? new Collection();
    }
}
