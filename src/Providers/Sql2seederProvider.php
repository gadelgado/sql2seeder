<?php

namespace Gadelgado\Sql2seeder\Providers;

use Gadelgado\Sql2seeder\Commands\TestCommand;
use Illuminate\Support\ServiceProvider;

class Sql2seederProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        if ($this->app->runningInConsole()) {
            $this->commands([
                TestCommand::class,
            ]);
        }
    }
}
