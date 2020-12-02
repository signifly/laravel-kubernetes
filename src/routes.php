<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Signifly\Kubernetes\Kubernetes;

if (config('kubernetes.healthchecks')) {
    Route::get('/healthz/liveness', function (Request $request) {
        return Kubernetes::httpLiveness($request);
    });

    Route::get('/healthz/readiness', function (Request $request) {
        return Kubernetes::httpReadiness($request);
    });
}
