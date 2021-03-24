<?php

namespace Signifly\Kubernetes\Commands;

use Illuminate\Console\Command;
use Signifly\Kubernetes\Kubernetes;

class HorizonLivenessCommand extends Command
{
    protected $signature = 'horizon:liveness';

    protected $description = 'Liveness-check command for Kubernetes';

    public function handle()
    {
        return Kubernetes::horizonLiveness();
    }
}
