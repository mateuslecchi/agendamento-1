<?php
/** @noinspection UnknownInspectionInspection */
/** @noinspection SpellCheckingInspection */

/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models;

use App\Traits\Fmt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property integer $group_roles_id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property GroupRole $groupRole
 * @property Environment[] $environments
 * @property GroupMember[] $groupMembers
 * @property mixed role
 * @method static make(array $array)
 * @method static where(string $string, string $string1, mixed $getValue)
 * @method static find(int $id)
 * @method static create(array $array)
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

    public function newQuery(): Builder
    {
        return parent::newQuery()
            ->where('deleted', '=', 0)
            ->orderBy('id')
            ->orderBy('name');
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

    /** @noinspection PhpUnused */
    public function getRoleAttribute(): GroupRole
    {
        return $this->groupRole;
    }

    public function getFormattedRoleAttribute(): string
    {
        return Fmt::text($this->role?->name);
    }

    public function getFormattedNameAttribute(): string
    {
        return Fmt::text($this->name);
    }
}
