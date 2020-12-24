<?php
/** @noinspection UnknownInspectionInspection */
/** @noinspection SpellCheckingInspection */
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models;

use App\Traits\Fmt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property GroupMember[] $groupMembers
 * @property mixed group
 * @property null|GroupMember member
 * @method static make(array $array)
 * @method static find(int|string|null $id)
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasRoles;

    public const NAME = 'name';
    public const EMAIL = 'email';
    public const PASSWORD = 'password';

    protected $keyType = 'integer';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function newQuery(): Builder
    {
        return parent::newQuery()
            ->where('deleted', '=', 0)
            ->orderBy('name');
    }

    public function groupMembers(): HasMany
    {
        return $this->hasMany(GroupMember::class, 'users_id');
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function getGroupAttribute(): null | Group
    {
        return $this->groupMembers?->first()?->group;
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function getMemberAttribute(): null | GroupMember
    {
        return $this->groupMembers?->first();
    }

    public function hashPassword(): self
    {
        $this->password = Hash::make($this->password);
        return $this;
    }

    public function getFormattedNameAttribute(): string
    {
        return Fmt::text($this->name);
    }

    public function getFormattedGroupAttribute(): string
    {
        return Fmt::text($this->group?->name);
    }

    public function getFormattedRoleAttribute(): string
    {
        return Fmt::text($this->group?->role?->name);
    }
}
