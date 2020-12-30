<?php
/** @noinspection SpellCheckingInspection */
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models;

use App\Traits\Fmt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property Environment[] $environments
 * @method static make(array $array)
 */
class Block extends Model
{
    public const ID = 'id';
    public const NAME = 'name';
    protected $fillable = ['id', 'name', 'deleted', 'created_at', 'updated_at'];

    public function newQuery(): Builder
    {
        return parent::newQuery()
            ->where('deleted', '=', 0)
            ->orderBy('name');
    }

    public function environments(): HasMany
    {
        return $this->hasMany(Environment::class, 'blocks_id');
    }

    public function countSchedules(): int
    {
        return $this->environments()->get()->map(static function (Environment $environment) {
            return $environment->schedules()->count();
        })->sum();
    }

    public function getFormattedNameAttribute(): string
    {
        return Fmt::text($this->name);
    }
}
