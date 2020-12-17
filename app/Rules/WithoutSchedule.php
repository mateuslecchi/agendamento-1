<?php

namespace App\Rules;

use DateTime;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class WithoutSchedule implements Rule
{
    private DateTime $start_date;
    private DateTime $end_date;

    private int $count;

    private const Query = '
    SELECT
        COUNT(id) AS count
    FROM
        ((SELECT
            id
        FROM
            schedules
        WHERE
            schedules.environments_id = ?
                AND schedules.date = ?
                AND situations_id IN (1)
                AND (? BETWEEN (SELECT
                    schedules.start_time
                FROM
                    schedules
                WHERE
                    schedules.environments_id = ?
                        AND schedules.date = ?
                        AND situations_id IN (1)
                ORDER BY schedules.start_time ASC , schedules.end_time DESC
                LIMIT 0 , 1) AND (SELECT
                    schedules.end_time
                FROM
                    schedules
                WHERE
                    schedules.environments_id = ?
                        AND schedules.date = ?
                        AND situations_id IN (1)
                ORDER BY schedules.end_time DESC , schedules.start_time ASC
                LIMIT 0 , 1))) UNION DISTINCT (SELECT
            id
        FROM
            schedules
        WHERE
            schedules.environments_id = ?
                AND schedules.date = ?
                AND situations_id IN (1)
                AND (? BETWEEN (SELECT
                    schedules.start_time
                FROM
                    schedules
                WHERE
                    schedules.environments_id = ?
                        AND schedules.date = ?
                        AND situations_id IN (1)
                ORDER BY schedules.start_time ASC , schedules.end_time DESC
                LIMIT 0 , 1) AND (SELECT
                    schedules.end_time
                FROM
                    schedules
                WHERE
                    schedules.environments_id = ?
                        AND schedules.date = ?
                        AND situations_id IN (1)
                ORDER BY schedules.end_time DESC , schedules.start_time ASC
                LIMIT 0 , 1))) UNION DISTINCT (SELECT
            id
        FROM
            schedules
        WHERE
            schedules.environments_id = ?
                AND schedules.date = ?
                AND situations_id IN (1)
                AND (start_time BETWEEN ? AND ?
                OR end_time BETWEEN ? AND ?)
        LIMIT 0 , 1)) AS result;
    ';

    public function __construct(string $date, string $start_time, string $end_time)
    {
        if (DateTime::createFromFormat('Y-m-d', $date) === false) {
            throw new InvalidArgumentException("$date = {$date}, is invalid.");
        }

        if (DateTime::createFromFormat('H:i', $start_time) === false) {
            throw new InvalidArgumentException("$start_time = {$start_time}, is invalid.");
        }

        if (DateTime::createFromFormat('H:i', $end_time) === false) {
            throw new InvalidArgumentException("$end_time = {$end_time}, is invalid.");
        }

        $this->start_date = DateTime::createFromFormat('Y-m-d H:i:s', "{$date} {$start_time}:00");
        $this->end_date = DateTime::createFromFormat('Y-m-d H:i:s', "{$date} {$end_time}:00");
    }

    public function passes($attribute, $value): bool
    {
        $this->count = 0;
        $environment_id = $value;
        $date = $this->start_date->format('Y-m-d');
        $start_time = $this->start_date->format('H:i:s');
        $end_time = $this->end_date->format('H:i:s');

        $result = DB::select(
            DB::raw(self::Query),
            [
                $environment_id, $date, $end_time, $environment_id, $date, $environment_id, $date,
                $environment_id, $date, $start_time, $environment_id, $date, $environment_id, $date,
                $environment_id, $date, $start_time, $end_time, $start_time, $end_time,
            ]
        );

        $this->count = $result[0]->count;
        return !$result[0]->count;
    }

    public function message(): string
    {
        return __('text.rule.without-schedule.message', ['count' => $this->count]);
    }
}
