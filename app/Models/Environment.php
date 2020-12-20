<?php
/** @noinspection UnknownInspectionInspection */
/** @noinspection SpellCheckingInspection */

/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 * @method static make(array $array)
 * @method static where(string $string, string $string1, int $id)
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
    protected $fillable = ['groups_id', 'blocks_id', 'name', 'deleted', 'created_at', 'updated_at'];

    protected $with = [
        'block',
        'group',
    ];

    public function newQuery(): Builder
    {
        return parent::newQuery()
            ->where('deleted', '=', 0)
            ->orderBy('name')
            ->orderBy('blocks_id');
    }

    public static function byBlock(Block $block): Collection
    {
        return self::where('blocks_id', '=', $block->id)->get() ?? new Collection();
    }

    public static function byGroup(Group $group): Collection
    {
        return self::where('groups_id', '=', $group->id)->get() ?? new Collection();
    }

    /**
     * @return BelongsTo
     */
    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class, 'blocks_id');
    }

    /**
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'groups_id');
    }

    /**
     * @return HasMany
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'environments_id');
    }

    /** @noinspection PhpIncompatibleReturnTypeInspection
     * @noinspection PhpUnused
     */
    public function getBlockAttribute(): null | Block
    {
        return $this->block()?->first();
    }
}
