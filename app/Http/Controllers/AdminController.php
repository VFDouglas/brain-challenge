<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Models\Event;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;

class AdminController extends Controller
{
    public function events(): View|FoundationApplication|Factory|Application
    {
        return view('admin.events', ['events' => Event::query()]);
    }

    public function getEvent($id): array
    {
        $event = Event::query()->find($id);

        if (!$event) {
            return [];
        }
        return $event->get()->toArray() ?? [];
    }

    public function createEvent(AdminRequest $request): bool
    {
        $event            = new Event();
        $event->name      = $request->name;
        $event->location  = $request->location;
        $event->starts_at = $request->starts_at;
        $event->ends_at   = $request->ends_at;
        $event->status    = $request->status;

        return $event->save();
    }

    public function editEvent($id, AdminRequest $request): bool
    {
        $event = Event::query()->find($id);
        if (!$event) {
            return false;
        }

        $event->name      = $request->name;
        $event->location  = $request->location;
        $event->starts_at = $request->starts_at;
        $event->ends_at   = $request->ends_at;
        $event->status    = $request->status;

        return $event->save();
    }
}
