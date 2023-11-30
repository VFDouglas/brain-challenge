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
        $notification = Notification::query()->find($id);

        if (!$notification) {
            return [];
        }
        return $notification->toArray() ?? [];
    }

    public function createNotification(AdminRequest $request): array
    {
        $response = [];
        try {
            $checkNotification = Notification::query()
                ->where('title', '=', $request->title);

            if ($checkNotification->count() > 0) {
                throw new Exception(__('admin.notifications.notification_already_exists'));
            }
            $notification      = Notification::query()->create([
                'title'       => $request->title,
                'description' => $request->description,
                'status'      => $request->input('status', false)
            ]);
            $response['error'] = '';
            $response['user']  = $notification->get()->toArray();
        } catch (Exception $e) {
            $response['error'] = $e->getMessage();
        }
        return $response;
    }

    public function editNotification($id, AdminRequest $request): array
    {
        $response = [];
        try {
            $notification = Notification::query()->find($id);

            if (!$notification) {
                throw new Exception(__('admin.users.user_not_found'));
            }

            $notification->title       = $request->title;
            $notification->description = $request->description;
            $notification->status      = $request->status;
            $notification->save();

            $response['error'] = '';
            $response['user']  = $notification->get()->toArray();
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
            $notification = Notification::query()->find($id);

            if (!$notification) {
                throw new Exception(__('admin.notifications.notification_not_found'));
            }
            $notification->delete();

            $response['error']        = '';
            $response['notification'] = $notification->get()->toArray();
        } catch (Exception $e) {
            switch (true) {
                case str_contains($e->getMessage(), 'Cannot delete or update a parent row'):
                    $response['error'] = __('admin.notifications.cannot_delete_parent_user');
                    break;
                default:
                    $response['error'] = $e->getMessage();
                    break;
            }
        }
        return $response;
    }
}
