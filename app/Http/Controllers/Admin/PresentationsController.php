<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Event;
use App\Models\Presentation;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;

class PresentationsController extends Controller
{
    public function presentations(): View|FoundationApplication|Factory|Application
    {
        $events     = Event::query()->where('status', '=', 1);
        $firstEvent = $events->count() > 0 ? $events->get()->toArray()[0]['id'] : null;
        $eventId    = request('eventId') ?? $firstEvent;

        $presentations = Presentation::query()
            ->select([
                'presentations.*',
                'users.name as username'
            ])
            ->join('users', 'presentations.user_id', '=', 'users.id')
            ->where('presentations.event_id', '=', $eventId);

        return view('admin.presentations', [
            'presentations' => $presentations,
            'users'         => User::query()->where('role', '=', 'P')->where('event_id', '=', $eventId),
            'events'        => $events,
            'eventId'       => $eventId
        ]);
    }

    public function getPresentation($id): array
    {
        $presentation = Presentation::query()->find($id);

        if (!$presentation) {
            return [];
        }
        return $presentation->toArray() ?? [];
    }

    public function createPresentation(AdminRequest $request): array
    {
        $response = [];
        try {
            $checkPresentation = Presentation::query()
                ->where('event_id', '=', $request->event_id)
                ->where('user_id', '=', $request->user_id);

            if ($checkPresentation->count() > 0) {
                throw new Exception(__('admin.presentations.presentation_already_exists'));
            }
            $user              = Presentation::query()->create([
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

    public function editPresentation($id, AdminRequest $request): array
    {
        $response = [];
        try {
            $user = Presentation::query()->find($id);

            if (!$user) {
                throw new Exception(__('admin.users.user_not_found'));
            }

            $user->name   = $request->name;
            $user->email  = $request->email;
            $user->status = $request->status;
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

    public function deletePresentation($id): array
    {
        $response = [];
        try {
            $user = Presentation::query()->find($id);

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
