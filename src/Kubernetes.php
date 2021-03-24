<?php

namespace Signifly\Kubernetes;

use Closure;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Kubernetes
{
    public static $horizonReadinessCallback = null;
    public static $horizonLivenessCallback = null;
    public static $httpReadinessCallback = null;
    public static $httpLivenessCallback = null;
    public static $checkUsing = null;
    public static $loginUsing = null;

    protected static $requestId = null;

    public static function requestId()
    {
        return self::$requestId
                ?? request()->header('X-Trace-Id')
                ?? request()->header('X-Request-Id')
                ?? request()->header('X-Amzn-Trace-Id')
                ?? self::$requestId = Str::orderedUuid();
    }

    public static function horizonReadiness($request = null)
    {
        return (static::$horizonReadinessCallback ?: function () {
            Artisan::call('horizon:status');
            $horizonStatus = Artisan::output();

            $this->comment(trim($horizonStatus));

            // 0 = healthy, 1 = unhealthy.
            return Str::contains($horizonStatus, 'running') ? 0 : 1;
        })($request);
    }

    public static function useHorizonReadinessCallback(Closure $callback)
    {
        static::$horizonReadinessCallback = $callback;

        return new static;
    }

    public static function horizonLiveness($request = null)
    {
        return (static::$horizonLivenessCallback ?: function () {
            Artisan::call('horizon:status');
            $horizonStatus = Artisan::output();

            $this->comment(trim($horizonStatus));

            // 0 = healthy, 1 = unhealthy.
            return Str::contains($horizonStatus, 'running') ? 0 : 1;
        })($request);
    }

    public static function useHorizonLivenessCallback(Closure $callback)
    {
        static::$horizonLivenessCallback = $callback;

        return new static;
    }

    public static function httpReadiness($request = null)
    {
        return (static::$httpReadinessCallback ?: function () {
            return 'ok';
        })($request);
    }

    public static function useHttpReadinessCallback(Closure $callback)
    {
        static::$httpReadinessCallback = $callback;

        return new static;
    }

    public static function httpLiveness($request = null)
    {
        return (static::$httpLivenessCallback ?: function () {
            return 'ok';
        })($request);
    }

    public static function useHttpLivenessCallback(Closure $callback)
    {
        static::$httpLivenessCallback = $callback;

        return new static;
    }

    public static function check($request, $next)
    {
        return (static::$checkUsing ?: function () {
            return Auth::check();
        })($request, $next);
    }

    public static function useCheck(Closure $callback)
    {
        static::$checkUsing = $callback;

        return new static;
    }

    public static function login($email, $request, $next)
    {
        return (static::$loginUsing ?: function () {
        })($email, $request, $next);
    }

    /**
     * Provide a callback to use when authenticating via HTTP header.
     *
     * The callback should login the user, e.g. Auth::login(..), as well
     * as create the user if it does not already exist. The callback should
     * return a response or redirect.
     *
     * @param Closure $callback
     * @return static
     */
    public static function useLogin(Closure $callback)
    {
        static::$loginUsing = $callback;

        return new static;
    }
}
