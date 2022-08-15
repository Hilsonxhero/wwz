<?php

namespace Modules\RolePermissions\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\RolePermissions\Repository\PermissionRepository;
use Modules\RolePermissions\Repository\PermissionRepositoryInterface;
use Modules\RolePermissions\Repository\RoleRepository;
use Modules\RolePermissions\Repository\RoleRepositoryInterface;

class RolePermissionsRepoServiceProvider extends ServiceProvider
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
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
    }
}
