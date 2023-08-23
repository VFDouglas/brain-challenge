<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class Question extends Model
{
    use HasFactory;

    public static function getAvailablePresentations(): Builder
    {
        $presentations = self::query()
            ->from('questions', 'q')
            ->select([
                'q.id as question_id',
                'p.id as presentation_id',
                'p.name',
                'q.description',
                'q.bonus'
            ])
            ->join('presentation_visits as pv', 'q.presentation_id', '=', 'pv.presentation_id')
            ->join('presentations as p', 'q.presentation_id', '=', 'p.id')
            ->where('pv.event_id', '=', session('event_access.event_id'))
            ->where('pv.user_id', '=', session('event_access.user_id'))
            ->whereRaw('now() between p.starts_at and p.ends_at')
            ->where('p.status', '=', 1)
            ->whereNotExists(
                Answer::query()
                    ->selectRaw(1)
                    ->from('answers', 'a')
                    ->whereColumn('a.presentation_id', '=', 'p.id')
                    ->whereColumn('a.question_id', '=', 'q.id')
                    ->where('a.user_id', '=', session('event_access.user_id'))
            );
        if (session()->has('question_id')) {
            $presentations->where('q.id', '=', session('question_id'));
            $presentations->where('q.presentation_id', '=', session('presentation_id'));
        }
        $presentations
            ->orderByDesc('q.bonus')
            ->orderByRaw('rand()')
            ->limit(1);

        return $presentations;
    }
}
