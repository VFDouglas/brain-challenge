<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public static function getCurrentEvent(): Builder
    {
        return self::query()
            ->whereRaw('now() between starts_at and ends_at')
            ->where('status', '=', 1);
    }
}
