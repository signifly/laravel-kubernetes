<?php


namespace Signifly\Kubernetes\Http;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Signifly\Kubernetes\Kubernetes;

class KubernetesController extends Controller
{
    public function readiness(Request $request)
    {
        return Kubernetes::httpReadiness($request);
    }

    public function liveness(Request $request)
    {
        return Kubernetes::httpLiveness($request);
    }
}
