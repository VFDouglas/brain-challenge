<?php

namespace App\Observers;

use App\Models\Answer;
use App\Models\DetailedScore;
use App\Models\Presentation;

class AnswerObserver
{
    /**
     * Handle the Answer "created" event.
     */
    public function created(Answer $answer): void
    {
        $description = Presentation::query()
            ->selectRaw('case when options.correct = 1 then questions.points else 0 end points')
            ->selectRaw('concat("Answer: ", questions.description) description')
            ->join('questions', 'presentations.id', '=', 'questions.presentation_id')
            ->join('options', 'questions.id', '=', 'options.question_id')
            ->where('questions.id', '=', $answer->question_id)
            ->where('options.id', '=', $answer->option_id)
            ->where('presentations.id', '=', $answer->presentation_id)
            ->get()
            ->toArray();

        DetailedScore::query()
            ->create([
                'event_id'        => $answer->event_id,
                'user_id'         => $answer->user_id,
                'presentation_id' => $answer->presentation_id,
                'answer_id'       => $answer->id,
                'question_id'     => $answer->question_id,
                'option_id'       => $answer->option_id,
                'description'     => $description[0]['description'],
                'score'           => $description[0]['points'],
            ]);
    }
}
