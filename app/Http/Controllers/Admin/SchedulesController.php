<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Event;
use App\Models\Presentation;
use App\Models\Schedule;
use Exception;
use Illuminate\Contracts\Foundation\Application as ContractsFoundationApplication;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;

class SchedulesController extends Controller
{
    public function schedules(): View|FoundationApplication|Factory|ContractsFoundationApplication
    {
        $events     = Event::query();
        $firstEvent = $events->count() > 0 ? $events->get()->toArray()[0]['id'] : null;
        $eventId    = request('eventId') ?? $firstEvent;

        $schedules = Schedule::query()
            ->where('event_id', '=', $eventId);

        return view('admin.schedules', [
            'schedules' => $schedules,
            'events'    => $events,
            'eventId'   => $eventId
        ]);
    }

    public function getSchedule($id): array
    {
        $presentation = Schedule::query()->find($id);

        if (!$presentation) {
            return [];
        }
        return $presentation->toArray() ?? [];
    }

    public function createSchedule(AdminRequest $request): array
    {
        $response = [];
        try {
            $checkSchedule = Schedule::query()
                ->where('event_id', '=', $request->event_id);

            if ($checkSchedule->count() > 0) {
                throw new Exception(__('admin.schedules.schedule_already_exists'));
            }
            $user              = Schedule::query()->create([
                'event_id'    => $request->event_id,
                'title'       => $request->title,
                'description' => $request->description,
                'starts_at'   => $request->starts_at,
                'ends_at'     => $request->ends_at
            ]);
            $response['error'] = '';
            $response['user']  = $user->get()->toArray();
        } catch (Exception $e) {
            $response['error'] = $e->getMessage();
        }
        return $response;
    }

    public function editSchedule($id, AdminRequest $request): array
    {
        $response = [];
        try {
            $schedule = Schedule::query()->find($id);

            if (!$schedule) {
                throw new Exception(__('admin.users.user_not_found'));
            }

            $schedule->title       = $request->title;
            $schedule->description = $request->description;
            $schedule->starts_at   = $request->starts_at;
            $schedule->ends_at     = $request->ends_at;
            $schedule->save();

            $response['error'] = '';
            $response['user']  = $schedule->get()->toArray();
        } catch (Exception $e) {
            switch (true) {
                case str_contains(strtoupper($e->getMessage()), 'DUPLICATE ENTRY'):
                    $response['error'] = __('admin.users.user_already_exists');
                    break;
                default:
                    $response['error'] = $e->getMessage();
                    break;
            }
        }
        return $response;
    }

    public function deleteSchedule($id): array
    {
        $response = [];
        try {
            $schedule = Schedule::query()->find($id);

            if (!$schedule) {
                throw new Exception(__('admin.users.user_not_found'));
            }
            $schedule->delete();

            $response['error'] = '';
            $response['user']  = $schedule->get()->toArray();
        } catch (Exception $e) {
            switch (true) {
                case str_contains($e->getMessage(), 'Cannot delete or update a parent row'):
                    $response['error'] = __('admin.users.cannot_delete_parent_user');
                    break;
                default:
                    $response['error'] = $e->getMessage();
                    break;
            }
        }
        return $response;
    }
}
