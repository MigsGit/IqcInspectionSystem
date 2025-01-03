<?php

namespace App\Providers;

use App\Jobs\CommonJob;
use App\Jobs\ResourceJob;
use App\Interfaces\CommonInterface;
use App\Interfaces\ResourceInterface;
use Illuminate\Support\ServiceProvider;

class SolidServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ResourceInterface::class, ResourceJob::class);
        $this->app->bind(CommonInterface::class, CommonJob::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
