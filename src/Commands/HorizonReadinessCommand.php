<?php

namespace Signifly\Kubernetes\Commands;

use Illuminate\Console\Command;
use Signifly\Kubernetes\Kubernetes;

class HorizonReadinessCommand extends Command
{
    protected $signature = 'horizon:readiness';

    protected $description = 'Readiness-check command for Kubernetes';

    public function handle()
    {
        return Kubernetes::horizonReadiness();
    }
}
