<?php

namespace App\Providers;


use App\Repositories\Contracts\IProperty;
use App\Repositories\Eloquent\PropertyRepository;
use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
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
        $this->app->bind(IProperty::class, PropertyRepository::class);
    }
}
