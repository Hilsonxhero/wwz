<?php

namespace Modules\User\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\User\Repository\UserAddressRepository;
use Modules\User\Repository\UserAddressRepositoryInterface;
use Modules\User\Repository\UserRepository;
use Modules\User\Repository\UserRepositoryInterface;

class UserRepoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserAddressRepositoryInterface::class, UserAddressRepository::class);
    }
}
