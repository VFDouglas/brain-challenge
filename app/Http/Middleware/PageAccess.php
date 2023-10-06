<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Psy\Readline\Hoa\Exception;

class PageAccess
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        try {
            // If the request is an AJAX, we skip the middleware
            if ($request->ajax()) {
                return $next($request);
            }

            /**
             * Array with parameters for the redirect exception
             */
            $redirect = [];
            if (!session()->has('event_access')) {
                $access = DB::connection('brain_challenge')
                    ->table('users')
                    ->leftJoin('events', function ($join) {
                        $join->on('events.id', '=', 'users.event_id');
                        $join->on('events.status', '=', DB::raw('1'));
                    })
                    ->select([
                        'users.id as user_id',
                        'users.name',
                        'users.accepted_terms',
                        'users.event_id',
                        'users.role',
                        'events.name as event_name'
                    ])
                    ->where('users.id', '=', session('user_id'))
                    ->get()
                    ->toArray();
                session(['event_access' => (array)reset($access) ?? []]);
            }
            $access = session('event_access');

            // If the user doesn't have access to the home page and is not an administrator
            if (empty($access) && !$request->is('403') && session('role') != 'A') {
                $redirect['url'] = '403';
                throw new Exception(json_encode($redirect));
            }

            // If the user didn't accept the terms
            if (
                (!array_key_exists('accepted_terms', $access) || $access['accepted_terms'] != 1) &&
                !$request->is('acceptance_terms')
            ) {
                $redirect['url'] = 'acceptance_terms';
                throw new Exception(json_encode($redirect));
            } elseif (
                array_key_exists('accepted_terms', $access) &&
                $access['accepted_terms'] == 1 &&
                $request->is('acceptance_terms')
            ) {
                $redirect['url'] = 'home';
                throw new Exception(json_encode($redirect));
            }

            // Checking if the user doesn't have access to the page he is trying to access
            if (!session()->has('page_access')) {
                $pages = DB::table('pages')
                    ->join('page_user', 'pages.id', '=', 'page_user.page_id')
                    ->select([
                        'pages.name',
                        'pages.url',
                    ])
                    ->where('page_user.user_id', '=', $access['user_id'] ?? '')
                    ->where('page_user.event_id', '=', $access['event_id'] ?? '')
                    ->where('pages.status', '=', 1)
                    ->get();
                session(['page_access' => $pages->all() ?? []]);

                // Adding default pages that doesn't require permission
                session()->push('page_access', (object)['name' => 'Acceptance Terms', 'url' => '/acceptance_terms']);
                session()->push('page_access', (object)['name' => 'Home', 'url' => '/home']);
                session()->push('page_access', (object)['name' => 'Home', 'url' => '/']);
            }


            /**
             * Determines if the user has access to the requested page
             */
            $pageAccess = false;
            foreach (session('page_access') as $item) {
                if ($item->url == $request->getPathInfo()) {
                    $pageAccess = true;
                }
            }

            if (!$pageAccess && $request->segment(1) != 'admin') {
                $redirect['url']    = '403';
                $redirect['params'] = [
                    'page'    => Str::headline($request->path()),
                    'message' => '403.page_access_message'
                ];
                throw new Exception(json_encode($redirect));
            }
        } catch (Exception $e) {
            $exception = json_decode($e->getMessage(), true);
            return redirect($exception['url'])->with($exception['params'] ?? []);
        }
        return $next($request);
    }
}
