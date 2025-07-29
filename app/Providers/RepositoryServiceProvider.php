<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use app\Repositories\Eloquent\{
    PostEloquent,
    TenantEloquent,
    CategoryEloquent
};
use app\Repositories\Interfaces\{
    PostInterface,
    TenantInterface,
    CategoryInterface
};
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PostInterface::class, PostEloquent::class);
        $this->app->bind(TenantInterface::class, TenantEloquent::class);
        $this->app->bind(CategoryInterface::class, CategoryEloquent::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
