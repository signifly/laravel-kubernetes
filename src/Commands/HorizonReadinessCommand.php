<?php

namespace Signifly\Kubernetes\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class HorizonReadinessCommand extends Command
{
    protected $signature = 'horizon:readiness';

    protected $description = 'Readiness-check command for Kubernetes';

    public function handle()
    {
        Artisan::call('horizon:status');
        $horizonStatus = Artisan::output();

        $this->comment(trim($horizonStatus));

        // 0 = healthy, 1 = unhealthy.
        return Str::contains($horizonStatus, 'running') ? 0 : 1;
    }
}
