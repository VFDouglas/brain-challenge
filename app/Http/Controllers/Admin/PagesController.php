<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Event;
use App\Models\Page;
use App\Models\PageUser;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;

class PagesController extends Controller
{
    public function pages(): View|FoundationApplication|Factory|Application
    {
        $events  = Event::query()->where('status', '=', 1);
        $eventId = request('eventId', $events->count() > 0 ? $events->get()->toArray()[0]['id'] : null);

        $users = User::query()->where('role', '=', 'S')->where('event_id', '=', $eventId);
        $pages = Page::query();

        return view('admin.pages', [
            'users'   => $users,
            'pages'   => $pages,
            'events'  => $events,
            'eventId' => $eventId
        ]);
    }

    public function getPage(AdminRequest $request): array
    {
        return User::query()
            ->leftJoin('page_user', function ($join) use ($request) {
                $join->on('users.id', '=', 'page_user.user_id');
                $join->where('page_user.page_id', '=', $request->id);
            })
            ->where('users.role', '=', 'S')
            ->get()
            ->toArray();
    }
}
