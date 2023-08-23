<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class SchedulesController extends Controller
{
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $schedulesDays = Schedule::getScheduleDays();
        $schedules     = Schedule::getEventSchedule();

        return view('schedules', ['schedulesDays' => $schedulesDays, 'schedules' => $schedules]);
    }
}
