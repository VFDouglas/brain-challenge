<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class Schedule extends Model
{
    use HasFactory;

    protected $connection = 'brain_challenge';
    protected $table      = 'schedules';

    public static function getScheduleDays(): Collection|array
    {
        return self::query()
            ->selectRaw('DISTINCT DATE(starts_at) as starts_at')
            ->selectRaw('DAY(starts_at) as day')
            ->where('event_id', '=', session('event_access.event_id'))
            ->orderBy('starts_at')
            ->get();
    }

    public static function getEventSchedule(): Collection|array
    {
        return self::query()
            ->select([
                'id',
                'title',
                'description',
                'starts_at',
                'ends_at',
            ])
            ->selectRaw('now() as cur_date')
            ->selectRaw('DAY(starts_at) day')
            ->where('event_id', '=', session('event_access.event_id'))
            ->orderBy('starts_at')
            ->get();
    }
}
