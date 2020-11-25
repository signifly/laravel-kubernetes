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
            "        \App\Http\Middleware\TrustProxies::class,\n",
            '',
            $content
        );

        $newMiddleware = collect([
            TrustProxies::class,
            ForceLoginViaForwardedUser::class,
        ])
            ->map(fn ($class) => "\t\t\\{$class}::class,")
            ->join("\n");

        $content = Str::replaceFirst(
            'protected $middleware = [',
            "protected \$middleware = [\n".$newMiddleware,
            $content
        );

        file_put_contents($kernel, $content);
        $this->comment('Patched HttpKernel with TrustProxies');
    }
}
