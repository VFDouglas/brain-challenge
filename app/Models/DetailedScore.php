<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailedScore extends Model
{
    use HasFactory;

    protected $table = 'detailed_score';
    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'user_id',
        'presentation_id',
        'answer_id',
        'question_id',
        'option_id',
        'description',
        'score'
    ];

    /**
     * Retrieve a detailed score for a specific event and user.
     *
     * @return array An array containing the detailed score information.
     */
    public static function detailedScore(): array
    {
        return self::query()
            ->where('event_id', '=', session('event_access.event_id'))
            ->where('user_id', '=', session('event_access.user_id'))
            ->get()
            ->toArray();
    }
}
