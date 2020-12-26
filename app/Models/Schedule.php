<?php /** @noinspection SpellCheckingInspection */

/** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models;


use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

/**
 * @property integer $id
 * @property integer $environments_id
 * @property integer $for
 * @property integer $by
 * @property integer $situations_id
 * @property string $date
 * @property string $start_time
 * @property string $end_time
 * @property string $created_at
 * @property string $updated_at
 * @property Environment $environment
 * @property Situation $situation
 * @method static make(array $array)
 * @method static where(string $string, string $string1, int $id)
 * @method static whereIn(string $string, $all)
 */
class Schedule extends Model
{

    public const ID = 'id';
    public const ENVIRONMENT_ID = 'environments_id';
    public const FOR = 'for';
    public const BY = 'by';
    public const SITUATION_ID = 'situations_id';
    public const DATE = 'date';
    public const START_TIME = 'start_time';
    public const END_TIME = 'end_time';

    protected $keyType = 'integer';

    protected $fillable = [
        'id',
        'environments_id',
        'for',
        'by',
        'situations_id',
        'date',
        'start_time',
        'end_time',
        'created_at',
        'updated_at'
    ];

    protected $with = [
        'environment',
        'situation'
    ];

    public function newQuery(): Builder
    {
        return parent::newQuery()
            ->orderBy('date')
            ->orderBy('start_time');
    }

    public static function byEnvironmentBuilder(Environment $environment): Builder
    {
        return self::where('environments_id', '=', $environment->id);
    }

    public static function byGroupBuilder(Group $group): Builder
    {
        return self::where('by','=', $group->id);
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public static function byGroupEnvironmentBuilder(Group $group): Builder
    {
        return self::whereIn('environments_id', $group->environments->pluck('id')->all());
    }

    public static function byGroupAndBlockBuilder(Group $group, Block $block): Builder
    {
        return self::where('by', '=', $group->id)->whereIn(
            'environments_id',
            Environment::byBlock($block)
                ?->pluck('id')
                ->all()
        );
    }

    public static function betweenDatesBuilder(Builder $query, DateTime $start, DateTime $end): Builder
    {
        return $query
            ->whereDate('date', '>=', $start)
            ->whereDate('date', '<=', $end);
    }

    public static function byGroupForCalendar(Group $group, DateTime $start, DateTime $end): Collection
    {
        return self::betweenDatesBuilder(self::byGroupBuilder($group), $start, $end)
            ->whereIn('situations_id', [\App\Domain\Enum\Situation::CONFIRMED()->getValue(), \App\Domain\Enum\Situation::PENDING()->getValue()])
            ->get();
    }

    public static function byEnvironmentForCalendar(Environment $environment, DateTime $start, DateTime $end): Collection
    {
        return self::betweenDatesBuilder(self::byEnvironmentBuilder($environment), $start, $end)
            ->whereIn('situations_id', [\App\Domain\Enum\Situation::CONFIRMED()->getValue(), \App\Domain\Enum\Situation::PENDING()->getValue()])
            ->get();
    }

    public static function byGroupAndBlockForCalendar(Group $group, Block $block, DateTime $start, DateTime $end): Collection
    {
        return self::betweenDatesBuilder(self::byGroupAndBlockBuilder($group, $block), $start, $end)
            ->whereIn('situations_id', [\App\Domain\Enum\Situation::CONFIRMED()->getValue(), \App\Domain\Enum\Situation::PENDING()->getValue()])
            ->get();
    }

    public static function afterOrEqualDateCollection(Builder $query, DateTime $dateTime): Collection
    {
        return $query->where('date', '>=', $dateTime->format('Y-m-d'))->get() ?? new Collection();
    }

    /**
     * @return Group|null
     * @noinspection PhpIncompatibleReturnTypeInspection
     */
    public function forGroup(): ?Group
    {
        return $this->belongsTo(Group::class, 'for')->first();
    }

    /**
     * @return Group|null
     * @noinspection PhpIncompatibleReturnTypeInspection
     * @noinspection PhpUnused
     */
    public function byGroup(): ?Group
    {
        return $this->belongsTo(Group::class, 'by')->first();
    }

    /**
     * @return BelongsTo
     */
    public function environment(): BelongsTo
    {
        return $this->belongsTo(Environment::class, 'environments_id');
    }

    /**
     * @return BelongsTo
     */
    public function situation(): BelongsTo
    {
        return $this->belongsTo(Situation::class, 'situations_id');
    }
}
