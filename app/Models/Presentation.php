<?php

namespace App\Models;

use App\Http\Requests\PresentationRequest;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class Presentation extends Model
{
    use HasFactory;

    protected $connection = 'brain_challenge';
    protected $table = 'presentations';

    protected $fillable = ['event_id', 'name', 'user_id', 'starts_at', 'ends_at', 'status'];

    /**
     * Get the questions of a presentation
     * @return Collection
     */
    public static function getPresentationQuestions(): Collection
    {
        return self::query()
            ->from('presentations', 'p')
            ->selectRaw('count(*) as qty')
            ->join('presentation_visits as pv', 'p.id', '=', 'pv.presentation_id')
            ->whereRaw('now() between p.starts_at and p.ends_at')
            ->pluck('qty');
    }

    /**
     * Save the QR Code read
     * @param PresentationRequest $request
     * @return array
     */
    public static function saveQRCode(PresentationRequest $request): array
    {
        $response = [];
        try {
            $presentationVisit = self::query()
                ->from('presentations', 'p')
                ->select([
                    'p.id',
                    'p.name',
                    'pv.created_at'
                ])
                ->leftJoin('presentation_visits as pv', function (JoinClause $join) {
                    $join->on('p.id', '=', 'pv.presentation_id');
                })
                ->where('p.qrcode', '=', $request->qrcode)
                ->where('p.event_id', '=', session('event_access.event_id'))
                ->get();
            if ($presentationVisit->count() == 0) {
                throw new Exception(__('qrcode.non_existent_qrcode', ['qrcode' => $request->qrcode]));
            }

            $pvArray = $presentationVisit->toArray();
            if (!empty($pvArray[0]['created_at'])) {
                throw new Exception(__('qrcode.presentation_already_visited', ['presentation' => $pvArray[0]['name']]));
            }

            $response['data'] = PresentationVisit::query()
                ->insert([
                    'event_id'        => session('event_access.event_id'),
                    'presentation_id' => $pvArray[0]['id'],
                    'user_id'         => session('event_access.user_id')
                ]);
        } catch (Exception $e) {
            $response['error'] = $e->getMessage();
        }
        return $response;
    }

    /**
     * Get the presentations the user is participating
     * @param PresentationRequest|Request $request
     * @return Builder
     */
    public static function getPresentations(PresentationRequest|Request $request): Builder
    {
        $presentations = Presentation::query()
            ->from('presentations as p')
            ->select(['name', 'pa.presentation_id as award_indicator'])
            ->selectSub(
                PresentationVisit::query()
                    ->from('presentation_visits as pv')
                    ->selectRaw('count(1)')
                    ->join('presentations', 'presentations.event_id', '=', 'pv.event_id')
                    ->where('pv.event_id', '=', session('event_access.event_id'))
                    ->where('pv.user_id', '=', session('event_access.user_id')),
                'amount_visit'
            )
            ->leftJoin('presentation_awards as pa', function (JoinClause $join) {
                $join
                    ->on('pa.presentation_id', '=', 'p.id')
                    ->on('pa.event_id', '=', 'p.event_id');
            })
            ->where('p.event_id', '=', session('event_access.event_id'));
        if (request()->has('presentation_name')) {
            $presentations->where('p.name', 'like', '%' . $request->presentation_name . '%');
        }
        return $presentations;
    }

    public static function getAwards(Request $request): Builder
    {
        $awards = Presentation::query()
            ->from('presentations', 'p')
            ->join('presentation_awards', function (JoinClause $join) {
                $join
                    ->on('presentation_awards.event_id', '=', 'p.event_id')
                    ->on('presentation_awards.presentation_id', '=', 'p.id');
            })
            ->select(['name'])
            ->selectSub(
                PresentationVisit::query()
                    ->from('presentation_visits as pv')
                    ->selectRaw('count(1)')
                    ->join('presentations', 'presentations.event_id', '=', 'pv.event_id')
                    ->where('pv.event_id', '=', session('event_access.event_id'))
                    ->where('pv.user_id', '=', session('event_access.user_id')),
                'amount_visit'
            )
            ->where('p.event_id', '=', session('event_access.event_id'))
            ->where('presentation_awards.user_id', '=', session('event_access.user_id'));
        if (request()->has('presentation_name')) {
            $awards->where('p.name', 'like', '%' . $request->presentation_name . '%');
        }
        return $awards;
    }
}
