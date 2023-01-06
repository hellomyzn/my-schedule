<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Events\EventMysqlRepository;
use App\Repositories\Reservations\ReservationsMysqlRepository;
use App\Repositories\Users\UserMysqlRepository;


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
        $this->app->bind(ReservationRepositoryInterface::class, ReservationsMysqlRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserMysqlRepository::class);
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
