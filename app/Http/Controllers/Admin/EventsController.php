<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Event;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;

class EventsController extends Controller
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
        return $event->toArray() ?? [];
    }

    public function createEvent(AdminRequest $request): bool
    {
        $event            = new Event();
        $event->name      = $request->name;
        $event->location  = $request->location;
        $event->starts_at = $request->starts_at;
        $event->ends_at   = $request->ends_at;
        $event->status    = (int)$request->input('status', 0);

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

    public function deleteEvent($id): array
    {
        $response = [];
        try {
            $event = Event::query()->find($id);

            if (!$event) {
                throw new Exception(__('admin.events.no_event_found'));
            }
            $event->delete();

            $response['error'] = '';
            $response['user']  = $event->get()->toArray();
        } catch (Exception $e) {
            switch (true) {
                case str_contains($e->getMessage(), 'Cannot delete or update a parent row'):
                    $response['error'] = __('admin.events.cannot_delete_parent_user');
                    break;
                default:
                    $response['error'] = $e->getMessage();
                    break;
            }
        }
        return $response;
    }
}
