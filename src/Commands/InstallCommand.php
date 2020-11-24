<?php

namespace Signifly\Kubernetes\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Signifly\Kubernetes\Middleware\ForceLoginViaForwardedUser;
use Signifly\Kubernetes\Middleware\TrustProxies;

class InstallCommand extends Command
{
    public $signature = 'kubernetes:install';

    public $description = 'Sets up integration for Kubernetes hosting';

    public function handle()
    {
        $kernel = app_path('Http/Kernel.php');
        $content = file_get_contents($kernel);

        $content = Str::replaceFirst(
            '\App\Http\Middleware\TrustProxies::class',
            '',
            $content
        );

        $newMiddleware = implode("\n\t", [
            TrustProxies::class,
            ForceLoginViaForwardedUser::class,
        ]);
        $content = Str::replaceFirst(
            'protected $middleware = [',
            $newMiddleware,
            $content
        );

        file_put_contents($kernel, $content);
        $this->comment('Patched HttpKernel with TrustProxies');
    }
}
