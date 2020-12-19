<?php
/** @noinspection SpellCheckingInspection */
/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models;

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
    /**
     * @var array
     */
    protected $fillable = ['name', 'deleted', 'created_at', 'updated_at'];

    public function newQuery(): Builder
    {
        return parent::newQuery()
            ->where('deleted', '=', 0);
    }

    /**
     * @return HasMany
     */
    public function environments(): HasMany
    {
        return $this->hasMany(Environment::class, 'blocks_id');
    }
}
