<?php

namespace Modules\Comment\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Comment\Repository\CommentRepository;
use Modules\Comment\Repository\CommentRepositoryInterface;
use Modules\Comment\Repository\ScoreModelRepository;
use Modules\Comment\Repository\ScoreModelRepositoryInterface;

class CommentRepoServiceProvider extends ServiceProvider
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
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(ScoreModelRepositoryInterface::class, ScoreModelRepository::class);
    }
}
