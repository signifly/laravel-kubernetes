<?php

namespace Signifly\Kubernetes;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Signifly\Kubernetes\Kubernetes
 */
class KubernetesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-kubernetes';
    }
}
