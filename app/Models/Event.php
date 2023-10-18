<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public static function getCurrentEvent(): Builder
    {
        return self::query()
            ->join('users', 'users.event_id', '=', 'events.id')
            ->whereRaw('now() between starts_at and ends_at')
            ->where('events.status', '=', 1);
    }
}
