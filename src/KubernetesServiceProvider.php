<?php

namespace Signifly\Kubernetes;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Signifly\Kubernetes\Commands\HorizonLivenessCommand;
use Signifly\Kubernetes\Commands\HorizonReadinessCommand;
use Signifly\Kubernetes\Commands\InstallCommand;

class KubernetesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/laravel-kubernetes.php' => config_path('kubernetes.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/laravel-kubernetes'),
            ], 'views');

            $migrationFileName = 'create_laravel_kubernetes_table.php';
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    __DIR__."/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/'.date('Y_m_d_His', time()).'_'.$migrationFileName),
                ], 'migrations');
            }

            $this->commands([
                InstallCommand::class,
            ]);

            if (config('kubernetes.healthchecks')) {
                $this->commands([
                    HorizonReadinessCommand::class,
                    HorizonLivenessCommand::class,
                ]);
            }
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-kubernetes');

        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-kubernetes.php', 'kubernetes');

        if (!$this->app->runningUnitTests()) {
            // Force logging to stdout/stderr
            Config::set('logging.default', 'stack');
            Config::set('logging.channels.stack', [
                'driver' => 'stack',
                'channels' => ['single', 'stderr', 'stdout'],
                'ignore_exceptions' => false,
            ]);
            Config::set('logging.channels.stderr', [
                'driver' => 'monolog',
                'handler' => StreamHandler::class,
                'level' => 'error',
                'formatter' => env('LOG_STDERR_FORMATTER', JsonFormatter::class),
                'with' => [
                    'stream' => 'php://stderr',
                    'bubble' => false,
                ],
            ]);
            Config::set('logging.channels.stdout', [
                'driver' => 'monolog',
                'handler' => StreamHandler::class,
                'formatter' => env('LOG_STDOUT_FORMATTER', JsonFormatter::class),
                'level' => 'debug',
                'with' => [
                    'stream' => 'php://stdout',
                ],
            ]);
        }
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = mb_strlen($migrationFileName);
        foreach (glob(database_path('migrations/*.php')) as $filename) {
            if ((mb_substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
}
