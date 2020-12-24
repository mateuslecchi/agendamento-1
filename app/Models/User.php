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
 * @property mixed member
 * @method static make(array $array)
 * @method static find(int|string|null $id)
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasRoles;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function newQuery(): Builder
    {
        return parent::newQuery()
            ->where('deleted', '=', 0)
            ->orderBy('name');
    }
    /**
     * @return HasMany
     */
    public function groupMembers(): HasMany
    {
        return $this->hasMany(GroupMember::class, 'users_id');
    }

    /** @noinspection PhpUndefinedMethodInspection
     * @noinspection PhpUnused
     */
    public function getGroupAttribute(): null | Group
    {
        return $this->groupMembers?->first()?->group;
    }

    /** @noinspection PhpUndefinedMethodInspection
     * @noinspection PhpUnused
     */
    public function getMemberAttribute(): null | GroupMember
    {
        return $this->groupMembers?->first();
    }

    /**
     * Criar uma hash direta a partir de qualquer valor salvo em senha.
     * @return $this
     */
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
