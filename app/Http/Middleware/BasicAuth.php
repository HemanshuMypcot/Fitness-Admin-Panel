<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use App\Utils\ResponseMessageUtils;

class BasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $AUTH_USER = 'admin';
        $AUTH_PASS = 'mypcot';
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        $is_not_authenticated = (!$has_supplied_credentials ||
            $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
            $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
        );
        if ($is_not_authenticated) {
            return ResponseMessageUtils::sendError(trans('auth.authentication_failed'));
        }
        if (!$request->header('uuid')) {
            return ResponseMessageUtils::sendError(trans('auth.uuid_required'));
            exit;
        }
        if (!$request->header('platform')) {
            return ResponseMessageUtils::sendError(trans('auth.platform_required'));
            exit;
        }
        $lang = $request->header('Accept-Language', null);
        $format = 'd M y';
        if ($lang != 'en') {
            $format = 'd F y';
        }
        if (!empty($lang)) {
            \App::setLocale($lang);
            Carbon::setLocale($lang);
        }
        \Session::flash('format',$format);
        
        return $next($request);
    }
}
