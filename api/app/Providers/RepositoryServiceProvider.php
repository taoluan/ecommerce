<?php

namespace App\Providers;
use App\Repositories\Interface\ProductInterface;
use App\Repositories\Interface\ProductInventoryInterface;
use App\Repositories\Interface\StoreInterface;
use App\Repositories\Interface\UserInterface;
use App\Repositories\ProductInventory;
use App\Repositories\ProductRepository;
use App\Repositories\StoreRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    protected $repositories = [
            UserInterface::class    => UserRepository::class,
            ProductInterface::class => ProductRepository::class,
            StoreInterface::class   => StoreRepository::class,
            ProductInventoryInterface::class => ProductInventory::class
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->repositories as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
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
