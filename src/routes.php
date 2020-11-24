<?php

use Illuminate\Support\Facades\Route;
use Signifly\Kubernetes\Kubernetes;

if (config('kubernetes.healthchecks')) {
    Route::get('/healthz/liveness', function ($request) {
        return Kubernetes::livenessCheck($request);
    });

    Route::get('/healthz/readiness', function ($request) {
        return Kubernetes::readinessCheck($request);
    });
}
