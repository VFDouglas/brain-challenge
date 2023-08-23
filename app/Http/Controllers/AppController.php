<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogRequest;
use App\Models\StudentLog;

/**
 * @author Douglas Vicentini (douglas.dvferreira@gmail.com)
 */
class AppController extends Controller
{
    public function logAccess(LogRequest $request): bool
    {
        return StudentLog::saveLog($request);
    }
}
