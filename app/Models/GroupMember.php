<?php
/** @noinspection SpellCheckingInspection */
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property integer $groups_id
 * @property integer $users_id
 * @property Group $group
 * @property User $user
 * @property Schedule[] $schedules
 * @method static create(array $array)
 * @method static where(string $string, string $string1, int $id)
 * @method static make(array $attributes)
 */
class GroupMember extends Model
{

    public const GROUP_ID = 'groups_id';
    public const USER_ID = 'users_id';


    protected $keyType = 'integer';

    protected $fillable = ['groups_id', 'users_id'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'groups_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'group_members_id');
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
