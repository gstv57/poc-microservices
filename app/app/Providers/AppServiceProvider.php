<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(!$this->app->isProduction());
        Model::handleLazyLoadingViolationUsing(function (Model $model, string $relation) {
            info("Attempted to lazy load [$relation] on model [$model]");
        });
    }
}
