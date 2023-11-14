<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Page;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;

class PagesController extends Controller
{
    public function pages(): View|FoundationApplication|Factory|Application
    {
        $users   = User::query()->where('role', '=', 'S');
        $pages   = Page::query();
        $events  = Event::query()->where('status', '=', 1);
        $eventId = request('eventId') ?? $events->count() > 0 ? $events->get()->toArray()[0]['id'] : null;

        return view('admin.pages', [
            'users'   => $users,
            'pages'   => $pages,
            'events'  => $events,
            'eventId' => $eventId
        ]);
    }
}
