<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Models\Event;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application as FoundationApplication;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

    public function users(): View|FoundationApplication|Factory|Application
    {
        return view('admin.users', ['users' => User::query()]);
    }

    public function getUser($id): array
    {
        $user = User::query()->find($id);

        if (!$user) {
            return [];
        }
        return $user->get()->toArray() ?? [];
    }

    public function createUser(AdminRequest $request): array
    {
        $response = [];
        try {
            $checkUser = User::query()->where('email', $request->email)->first();

            if ($checkUser) {
                throw new Exception(__('admin.users.user_already_exists'));
            }
            $user              = User::query()->create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make(Str::uuid()),
                'status'   => $request->status,
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
