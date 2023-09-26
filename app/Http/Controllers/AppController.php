<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogRequest;
use App\Models\Event;
use App\Models\SimplifiedScore;
use App\Models\StudentLog;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class AppController extends Controller
{
    public function index(): View|FoundationApplication|Factory|Application
    {
        return view('home', ['event' => Event::getCurrentEvent(), 'score' => SimplifiedScore::getScore()]);
    }

    public function logAccess(LogRequest $request): bool
    {
        return StudentLog::saveLog($request);
    }
}
