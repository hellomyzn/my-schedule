<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Repositories\Events\EventMysqlRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EventRepositoryInterface::class, EventMysqlRepository::class);
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
