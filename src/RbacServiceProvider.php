<?php
namespace Dnetix\Rbac;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Support\ServiceProvider;

class RbacServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'rbac');
    }
}
