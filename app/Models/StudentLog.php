<?php

namespace App\Models;

use App\Http\Requests\LogRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class StudentLog extends Model
{
    use HasFactory;

    public static function saveLog(LogRequest $request): bool
    {
        self::query()
            ->insert([
                'event_id'    => session('event_access.event_id'),
                'user_id'     => session('event_access.user_id'),
                'page'        => $request->page,
                'description' => $request->description
            ]);
        return true;
    }
}
