<?php

namespace App\Models;

use App\Traits\Fmt;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property Group[] $groups
 */
class GroupRole extends Model
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
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany('App\Models\Group', 'group_roles_id');
    }

    public function getFormattedNameAttribute(): string
    {
        return Fmt::text($this->name);
    }
}
