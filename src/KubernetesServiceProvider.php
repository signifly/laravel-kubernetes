<?php

namespace Signifly\Kubernetes;

use Illuminate\Support\ServiceProvider;
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
