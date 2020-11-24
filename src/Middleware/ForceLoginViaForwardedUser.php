<?php

namespace Signifly\Kubernetes\Middleware;

use Illuminate\Http\Request;
use Signifly\Kubernetes\Kubernetes;

class ForceLoginViaForwardedUser
{
    public function handle(Request $request, \Closure $next)
    {
        if (! config('kubernetes.intercept_auth') || Kubernetes::check($request, $next)) {
            return $next($request);
        }

        $email = $request->header('x-forwarded-user');
        if ($email) {
            return Kubernetes::login($email, $request, $next);
        }

        return $next($request);
    }
}
