<?php

use Illuminate\Support\Facades\Route;
use Signifly\Kubernetes\Http\KubernetesController;

if (config('kubernetes.healthchecks')) {
    Route::get('/healthz/liveness', [KubernetesController::class, 'liveness']);

    Route::get('/healthz/readiness', [KubernetesController::class, 'readiness']);
}
