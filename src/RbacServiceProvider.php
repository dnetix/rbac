<?php
namespace Dnetix\Rbac;

use Dnetix\Dates\DateRangeChecker;
use Dnetix\Rbac\Contracts\RbacRepository;
use Dnetix\Rbac\Repositories\EloquentRbacRepository;
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
        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'rbac');
        
        $this->app->bind(RbacRepository::class, EloquentRbacRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(GateContract $gate, RbacRepository $rbacRepository)
    {
        // Publishes the migrations for this RBAC module
        $this->publishes([
            __DIR__ . '/migrations/2016_04_20_014519_create_rbac_module.php' => base_path('database/migrations/2016_04_20_014519_create_rbac_module.php')
        ], 'migration');
        
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('rbac.php'),
        ], 'config');

        $this->definePermissions($gate, $rbacRepository);
        
        // Register the after an authorization check has been made to log the operations with the results
        if(config('rbac.log_callback')) {
            $gate->after(config('rbac.log_callback'));
        }
    }

    protected function definePermissions($gate, $repository)
    {
        foreach ($repository->getPermissions() as $permissionSlug => $extra){
            // Checks if the configuration for this permissions has a callback

            $gate->define($permissionSlug, function($user) use ($repository, $permissionSlug, $extra){

                // if the permission has a date range in which its allowed, but if its true still checks for the proper roles
                if(isset($extra['date_range']) && !DateRangeChecker::load($extra['date_range'])->check()){
                    return false;
                }

                $roles = $repository->getRolesByAuthenticatableAndPermission($user, $permissionSlug);
                // The user has at least one role that grants this permission
                if($roles->count() > 0){

                    if(isset($extra['callback']) && is_callable($extra['callback'])) {
                        // Returns the result for the callable function
                        return (call_user_func_array($extra['callback'], func_get_args()));
                    }else{
                        // There is no callable so its true
                        return true;
                    }

                }

                return false;

            });
        }
    }
}
