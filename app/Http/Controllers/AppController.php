<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogRequest;
use App\Models\DetailedScore;
use App\Models\Event;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\SimplifiedScore;
use App\Models\StudentLog;
use App\Models\User;
use App\Traits\FileTrait;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application as FoundationApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class AppController extends Controller
{
    use FileTrait;

    public function index(): View|FoundationApplication|Factory|Application
    {
        return view('home', ['event' => Event::getCurrentEvent(), 'score' => SimplifiedScore::getScore()]);
    }

    public function logAccess(LogRequest $request): bool
    {
        return StudentLog::saveLog($request);
    }

    public function detailedScore(): array
    {
        return DetailedScore::detailedScore();
    }

    public function readNotification($notificationId): array
    {
        $response = [];
        try {
            NotificationUser::query()
                ->where('event_id', '=', session('event_access.event_id'))
                ->where('user_id', '=', session('user_id'))
                ->where('notification_id', '=', $notificationId)
                ->update(['read_at' => DB::raw('now()')]);
        } catch (Exception $e) {
            $response['error'] = __('admin.notifications.error_read_notification') ?? $e->getMessage();
        }
        return $response;
    }

    public function getLoggedUser(): array
    {
        $user = User::query()->find(session('user_id'));

        return $user->toArray();
    }

    public function updateProfile(): array
    {
        $response = [];
        try {
            $user = User::query()->find(session('user_id'));

            if (!$user) {
                $response['error'] = __('header.error_update_profile');
            } else {
                $user->name  = request('name');
                $user->email = request('email');
                $user->save();
                $response['name'] = $user->name;
                session(['name' => $user->name]);
            }
        } catch (Exception $e) {
            $response['error'] = __('header.error_update_profile') ?? $e->getMessage();
        }
        return $response;
    }

    public function getNotifications(): array
    {
        return Notification::query()
            ->join('notification_user', 'notifications.id', '=', 'notification_user.notification_id')
            ->where('notification_user.event_id', '=', session('event_access.event_id'))
            ->where('user_id', '=', session('user_id'))
            ->orderByDesc('notifications.created_at')
            ->get()
            ->toArray();
    }

    public function exportDataToExcel($modelName): string
    {
        $modelFullName = "App\\Models\\" . Str::singular(Str::studly($modelName));

        if (!class_exists($modelFullName)) {
            return 'Parâmetro inválido';
        }
        /**
         * @var Model $model
         */
        $model = new $modelFullName();

        $data = $model::query()->get()->toArray();
        return $this->exportToExcel($data, $modelName);
    }
}
