<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogRequest;
use App\Models\DetailedScore;
use App\Models\Event;
use App\Models\NotificationUser;
use App\Models\SimplifiedScore;
use App\Models\StudentLog;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class AppController extends Controller
{
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
                ->update(['read_at' => now()->format('Y-m-d H:i:s')]);
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
}
