<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Event;
use App\Models\Presentation;
use App\Models\PresentationAward;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;

class AwardsController extends Controller
{
    public function awards(): View|FoundationApplication|Factory|Application
    {
        $events        = Event::query();
        $firstEvent    = $events->count() > 0 ? $events->get()->toArray()[0]['id'] : null;
        $eventId       = request('eventId') ?? $firstEvent;
        $presentations = Presentation::query()
            ->where('event_id', '=', $eventId);
        $users         = User::query()
            ->where('event_id', '=', $eventId)
            ->where('role', '=', 'S');

        $awards = PresentationAward::query()
            ->select([
                'presentation_awards.*',
                'presentations.name as presentation_name',
                'users.name as user_name',
            ])
            ->join('presentations', 'presentation_awards.presentation_id', '=', 'presentations.id')
            ->join('users', 'presentation_awards.user_id', '=', 'users.id')
            ->where('presentation_awards.event_id', '=', $eventId);

        return view('admin.awards', [
            'awards'        => $awards,
            'events'        => $events,
            'eventId'       => $eventId,
            'presentations' => $presentations,
            'users'         => $users
        ]);
    }

    public function getAward($id): array
    {
        $presentation = PresentationAward::query()->find($id);

        if (!$presentation) {
            return [];
        }
        return $presentation->toArray() ?? [];
    }

    public function createAward(AdminRequest $request): array
    {
        $response = [];
        try {
            $checkAward = PresentationAward::query()
                ->where('event_id', '=', $request->event_id)
                ->where('presentation_id', '=', $request->presentation_id)
                ->where('user_id', '=', $request->user_id);

            if ($checkAward->count() > 0) {
                throw new Exception(__('admin.awards.award_already_exists'));
            }
            $award             = PresentationAward::query()->create([
                'event_id'        => $request->event_id,
                'presentation_id' => $request->presentation_id,
                'user_id'         => $request->user_id,
            ]);
            $response['error'] = '';
            $response['awrad'] = $award->get()->toArray();
        } catch (Exception $e) {
            $response['error'] = $e->getMessage();
        }
        return $response;
    }

    public function editAward($id, AdminRequest $request): array
    {
        $response = [];
        try {
            $award = PresentationAward::query()->find($id);

            if (!$award) {
                throw new Exception(__('admin.users.user_not_found'));
            }

            $award->event_id        = $request->event_id;
            $award->presentation_id = $request->presentation_id;
            $award->user_id         = $request->user_id;
            $award->save();

            $response['error'] = '';
            $response['user']  = $award->get()->toArray();
        } catch (Exception $e) {
            switch (true) {
                case str_contains(strtoupper($e->getMessage()), 'DUPLICATE ENTRY'):
                    $response['error'] = __('admin.users.award_already_exists');
                    break;
                default:
                    $response['error'] = $e->getMessage();
                    break;
            }
        }
        return $response;
    }

    public function deleteAward($id): array
    {
        $response = [];
        try {
            $schedule = PresentationAward::query()->find($id);

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
