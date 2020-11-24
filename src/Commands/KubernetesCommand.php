<?php

namespace Signifly\Kubernetes\Commands;

use Illuminate\Console\Command;

class KubernetesCommand extends Command
{
    public $signature = 'laravel-kubernetes';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
