<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class Answer extends Model
{
    use HasFactory;

    protected $connection = 'brain_challenge';
    protected $table = 'answers';
    protected $fillable = ['event_id', 'user_id', 'presentation_id', 'question_id', 'option_id'];
    public $timestamps = false;

    /**
     * Get the amount of answers the user has for the current day
     * @return array
     */
    public static function getAmountAnswers(): array
    {
        return self::query()
            ->selectRaw('count(1) as amount_answers')
            ->where('event_id', '=', session('event_access.event_id'))
            ->where('user_id', '=', session('event_access.user_id'))
            ->whereRaw('date(created_at) = date(now())')
            ->get()
            ->toArray();
    }
}
