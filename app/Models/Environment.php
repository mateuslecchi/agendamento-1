<?php
/** @noinspection UnknownInspectionInspection */
/** @noinspection SpellCheckingInspection */

/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models;

use App\Traits\Fmt;
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
 * @property bool $automatic_approval
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
    public const NAME = 'name';
    public const GROUP_ID = 'groups_id';
    public const BLOCK_ID = 'blocks_id';
    public const AUTOMATIC_APPROVAL = 'automatic_approval';

    protected $keyType = 'integer';

    protected $fillable = [
        'id',
        'groups_id',
        'blocks_id',
        'name',
        'automatic_approval',
        'deleted',
        'created_at',
        'updated_at'
    ];

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

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class, 'blocks_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'groups_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'environments_id');
    }

    public function getBlockAttribute(): null | Block
    {
        return $this->block()?->first();
    }

    public function getFormattedNameAttribute(): string
    {
        return Fmt::text($this->name);
    }
}
