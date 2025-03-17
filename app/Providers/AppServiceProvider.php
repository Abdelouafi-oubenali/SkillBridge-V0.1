<?php

namespace App\Providers;

use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use App\Repositories\CourseRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepository;
use App\Repositories\TagRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\CourseRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );
    
        $this->app->bind(
            TagRepositoryInterface::class,
            TagRepository::class
        );;
        $this->app->bind(
            CourseRepositoryInterface::class,
            CourseRepository::class
        );
        $this->app->bind(
            UserRepositoryInterface::class,
             UserRepository::class);

    }

    public function boot()
    {
        //
    }
}