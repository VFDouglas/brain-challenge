<?php

namespace App\Providers;

use App\Models\Answer;
use App\Models\DetailedScore;
use App\Observers\AnswerObserver;
use App\Observers\DetailedScoreObserver;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Vite::useScriptTagAttributes([
            'defer' => true
        ]);

        Answer::observe(AnswerObserver::class);
        DetailedScore::observe(DetailedScoreObserver::class);
    }
}
