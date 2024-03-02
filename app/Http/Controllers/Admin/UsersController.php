<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Event;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function users(): View|FoundationApplication|Factory|Application
    {
        $events     = Event::query()->where('status', '=', 1);
        $firstEvent = $events->count() > 0 ? $events->get()->toArray()[0]['id'] : null;
        $eventId    = request('eventId') ?? $firstEvent;

        $users = User::query();
        if ($eventId == 0) {
            $users = $users->whereNull('event_id');
        } else {
            $users = $users->where('event_id', '=', $eventId);
        }
        return view('admin.users', [
            'users'   => $users,
            'events'  => $events,
            'eventId' => $eventId
        ]);
    }

    public function getUser($id): array
    {
        $user = User::query()->find($id);

        if (!$user) {
            return [];
        }
        return $user->toArray() ?? [];
    }

    public function createUser(AdminRequest $request): array
    {
        $response = [];
        try {
            $checkUser = User::query()
                ->where('email', $request->email)
                ->where('event_id', '=', $request->event_id)
                ->first();

            if ($checkUser) {
                throw new Exception(__('admin.users.user_already_exists'));
            }
            $user              = User::query()->create([
                'name'     => $request->name,
                'email'    => $request->email,
                'role'     => $request->role,
                'password' => Hash::make($request->password),
                'status'   => $request->input('status', false),
                'event_id' => $request->event_id
            ]);
            $response['error'] = '';
            $response['user']  = $user->get()->toArray();
        } catch (Exception $e) {
            $response['error'] = $e->getMessage();
        }
        return $response;
    }

    public function editUser($id, AdminRequest $request): array
    {
        $response = [];
        try {
            $user = User::query()->find($id);

            if (!$user) {
                throw new Exception(__('admin.users.user_not_found'));
            }

            $user->name     = $request->name;
            $user->email    = $request->email;
            $user->role     = $request->role;
            $user->status   = $request->status;
            $user->event_id = $request->event_id;
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

    public function deleteUser($id): array
    {
        $response = [];
        try {
            $user = User::query()->find($id);

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
