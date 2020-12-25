<?php
/** @noinspection UnknownInspectionInspection */
/** @noinspection SpellCheckingInspection */

/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models;

use App\Domain\Enum\GroupRoles;
use App\Traits\Fmt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property integer $id
 * @property integer $group_roles_id
 * @property string $name
 * @property bool $personal_group
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
    public const NAME = 'name';
    public const GROUP_ROLE_ID = 'group_roles_id';
    public const PERSONAL_GROUP = 'personal_group';

    protected $keyType = 'integer';

    protected $fillable = [
        'group_roles_id',
        'name',
        'personal_group',
        'deleted',
        'created_at',
        'updated_at'
    ];

    public function newQuery(): Builder
    {
        return parent::newQuery()
            ->where('deleted', '=', 0)
            ->orderBy('id')
            ->orderBy('name');
    }

    public static function byRole(GroupRoles $roles): Collection
    {
        return self::query()->where(self::GROUP_ROLE_ID, '=', $roles->getValue())->get();
    }

    public function groupRole(): BelongsTo
    {
        return $this->belongsTo(GroupRole::class, 'group_roles_id');
    }

    public function environments(): HasMany
    {
        return $this->hasMany(Environment::class, 'groups_id');
    }

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
