<?php

namespace App\Http\Middleware;

use Closure;
use App\Common\Constants\ErrorConst;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        try {
            $guard = Arr::get($guards, '0');
            $guard === config('packet.api.guard') && Auth::guard($guard)->getPayload();

            if ($guard && Auth::guard($guard)->check()) {
                return $next($request);
            }
        } catch (TokenExpiredException $e) {
            try {
                $token = Auth::guard($guard)->refresh();
                $request->headers->set('Refresh-Token', $token);
                $request->headers->set('Authorization', 'Bearer '. $token);
                Auth::guard($guard)->setToken($token)->user();
                return $next($request);
            } catch (JWTException $e) {
            }
        } catch (TokenInvalidException $e) {

        } catch (JWTException $e) {

        }

        throw new HttpException(ErrorConst::UNAUTHORIZED, ErrorConst::getError(ErrorConst::UNAUTHORIZED));
    }
}