<?php

namespace App\Observers;

use App\Models\DetailedScore;
use App\Models\SimplifiedScore;
use Illuminate\Support\Facades\DB;

class DetailedScoreObserver
{
    /**
     * Handle the DetailedScore "created" event.
     */
    public function created(DetailedScore $detailedScore): void
    {
        $userScore = SimplifiedScore::query()
            ->select('score')
            ->where('event_id', '=', $detailedScore->event_id)
            ->where('user_id', '=', $detailedScore->user_id)
            ->get()
            ->toArray();

        SimplifiedScore::query()
            ->upsert([
                'score'      => ($userScore[0]['score'] ?? 0) + $detailedScore->score,
                'event_id'   => $detailedScore->event_id,
                'user_id'    => $detailedScore->user_id,
                'updated_at' => DB::raw('now()')
            ], ['event_id', 'user_id']);
    }
}
