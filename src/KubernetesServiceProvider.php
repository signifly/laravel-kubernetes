<?php

namespace Signifly\Kubernetes;

use Illuminate\Support\ServiceProvider;
use Signifly\Kubernetes\Commands\KubernetesCommand;

class KubernetesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-kubernetes.php' => config_path('laravel-kubernetes.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/laravel-kubernetes'),
            ], 'views');

            $migrationFileName = 'create_laravel_kubernetes_table.php';
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                ], 'migrations');
            }

            $this->commands([
                KubernetesCommand::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-kubernetes');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-kubernetes.php', 'laravel-kubernetes');
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path("migrations/*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
}
