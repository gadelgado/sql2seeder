<?php

namespace Gadelgado\Sql2seeder\Providers;

use Gadelgado\Sql2Seeder\Commands\Sql2SeederCommand;
use Illuminate\Support\ServiceProvider;

class Sql2SeederProvider extends ServiceProvider
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
                Sql2SeederCommand::class,
            ]);
        }
    }
}
