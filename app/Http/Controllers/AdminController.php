<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Models\Event;
use App\Models\Presentation;
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
        return $user->toArray() ?? [];
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
                'role'     => $request->role,
                'password' => Hash::make(Str::uuid()),
                'status'   => $request->input('status', false)
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
            $user->role   = $request->role;
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

    public function presentations(): View|FoundationApplication|Factory|Application
    {
        return view('admin.presentations', [
            'presentations' => Presentation::query(),
            'users'         => User::query()->where('role', '=', 'P'),
            'events'        => Event::query()->where('status', '=', 1)
        ]);
    }

    public function getPresentation($id): array
    {
        $presentation = Presentation::query()->find($id);

        if (!$presentation) {
            return [];
        }
        return $presentation->get()->toArray() ?? [];
    }

    public function createPresentation(AdminRequest $request): array
    {
        $response = [];
        try {
            $checkPresentation = Presentation::query()
                ->where('email', $request->email)->first();

            if ($checkPresentation) {
                throw new Exception(__('admin.users.user_already_exists'));
            }
            $user              = Presentation::query()->create([
                'event_id'  => session('event_access.event_id'),
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
