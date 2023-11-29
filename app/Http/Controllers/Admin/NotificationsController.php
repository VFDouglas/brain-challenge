<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Event;
use App\Models\Notification;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application as FoundationApplication;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class NotificationsController extends Controller
{
    public function notifications(): View|Application|Factory|FoundationApplication
    {
        $users         = User::query()->where('role', '=', 'S');
        $events        = Event::query()->where('status', '=', '1');
        $eventId       = request('eventId', $events->count() > 0 ? $events->get()->toArray()[0]['id'] : null);
        $notifications = Notification::query();

        return view('admin.notifications', [
            'users'         => $users,
            'events'        => $events,
            'eventId'       => $eventId,
            'notifications' => $notifications,
        ]);
    }

    public function getNotification($id): array
    {
        $presentation = Notification::query()->find($id);

        if (!$presentation) {
            return [];
        }
        return $presentation->toArray() ?? [];
    }

    public function createNotification(AdminRequest $request): array
    {
        $response = [];
        try {
            $checkNotification = Notification::query()
                ->where('event_id', '=', $request->event_id)
                ->where('user_id', '=', $request->user_id);

            if ($checkNotification->count() > 0) {
                throw new Exception(__('admin.presentations.presentation_already_exists'));
            }
            $user              = Notification::query()->create([
                'event_id'  => $request->event_id,
                'name'      => $request->name,
                'user_id'   => $request->user_id,
                'starts_at' => $request->starts_at,
                'ends_at'   => $request->ends_at,
                'status'    => $request->input('status', false)
            ]);
            $response['error'] = '';
            $response['user']  = $user->get()->toArray();
        } catch (Exception $e) {
            $response['error'] = $e->getMessage();
        }
        return $response;
    }

    public function editNotification($id, AdminRequest $request): array
    {
        $response = [];
        try {
            $user = Notification::query()->find($id);

            if (!$user) {
                throw new Exception(__('admin.users.user_not_found'));
            }

            $user->name      = $request->name;
            $user->user_id   = $request->user_id;
            $user->starts_at = $request->starts_at;
            $user->ends_at   = $request->ends_at;
            $user->status    = $request->status;
            $user->save();

            $response['error'] = '';
            $response['user']  = $user->get()->toArray();
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

    public function deleteNotification($id): array
    {
        $response = [];
        try {
            $user = Notification::query()->find($id);

            if (!$user) {
                throw new Exception(__('admin.users.user_not_found'));
            }
            $user->delete();

            $response['error'] = '';
            $response['user']  = $user->get()->toArray();
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
