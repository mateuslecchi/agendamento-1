<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $groups_id
 * @property int $blocks_id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property Block $block
 * @property Group $group
 * @property Schedule[] $schedules
 */
class Environment extends Model
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
    protected $fillable = ['groups_id', 'blocks_id', 'name', 'created_at', 'updated_at'];

    public static function byBlock(Block $block): Collection
    {
        return self::where('blocks_id', '=', $block->id)->get() ?? new Collection();
    }

    public static function byGroup(Group $group): Collection
    {
        return self::where('groups_id', '=', $group->id)->get() ?? new Collection();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function block()
    {
        return $this->belongsTo('App\Models\Block', 'blocks_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('App\Models\Group', 'groups_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedules()
    {
        return $this->hasMany('App\Models\Schedule', 'environments_id');
    }

    public function getBlockAttribute(): null | Block
    {
        return $this->block()?->first();
    }
}
