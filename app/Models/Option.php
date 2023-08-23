<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class Option extends Model
{
    use HasFactory;

    public static function getQuestionOptions($questionId): Collection|array
    {
        return self::query()
            ->from('options', 'o')
            ->select([
                'o.presentation_id',
                'o.question_id',
                'o.id',
                'o.description',
                'o.correct'
            ])
            ->where('o.question_id', '=', $questionId)
            ->orderByRaw('rand()')
            ->get();
    }
}
