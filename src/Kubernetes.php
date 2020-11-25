<?php

namespace Signifly\Kubernetes;

use Closure;
use Illuminate\Support\Facades\Auth;

class Kubernetes
{
    public static $horizonReadinessCallback = null;
    public static $horizonLivenessCallback = null;
    public static $httpReadinessCallback = null;
    public static $httpLivenessCallback = null;
    public static $checkUsing = null;
    public static $loginUsing = null;

    public static function horizonReadiness($request)
    {
        return (static::$horizonReadinessCallback ?: function () {
            return 0; // 0 = success
        })($request);
    }

    public static function useHorizonReadinessCallback(Closure $callback)
    {
        static::$horizonReadinessCallback = $callback;

        return new static;
    }

    public static function horizonLiveness($request)
    {
        return (static::$horizonLivenessCallback ?: function () {
            return 0; // 0 = success
        })($request);
    }

    public static function useHorizonLivenessCallback(Closure $callback)
    {
        static::$horizonLivenessCallback = $callback;

        return new static;
    }

    public static function httpReadiness($request)
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

    public static function httpLiveness($request)
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
