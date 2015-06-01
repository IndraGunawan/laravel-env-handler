<?php

namespace IndraGunawan\LaravelEnvHandler;

use Illuminate\Support\ServiceProvider;

class EnvHandlerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands('IndraGunawan\LaravelEnvHandler\Console\EnvUpdateCommand');
    }
}
