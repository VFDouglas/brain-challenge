<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimplifiedScore extends Model
{
    use HasFactory;

    protected $table = 'simplified_score';

    public static function getScore(): Collection|array
    {
        return self::query()
            ->select([
                'score',
                'updated_at'
            ])
            ->where('event_id', '=', session('event_access.event_id'))
            ->where('user_id', '=', session('event_access.user_id'))
            ->get();
    }
}
