<?php

namespace Signifly\Kubernetes\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class HorizonLivenessCommand extends Command
{
    protected $signature = 'horizon:liveness';

    protected $description = 'Liveness-check command for Kubernetes';

    public function handle()
    {
        Artisan::call('horizon:status');
        $horizonStatus = Artisan::output();

        $this->comment(trim($horizonStatus));

        // 0 = healthy, 1 = unhealthy.
        return Str::contains($horizonStatus, 'running') ? 0 : 1;
    }
}
