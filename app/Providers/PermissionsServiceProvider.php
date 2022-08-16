<?php

namespace App\Providers;

use App\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider
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
        try {
            // Permission::get()->map(function ($permission) {
            //     Gate::define($permission->slug, function ($user) use ($permission) {
            //         return $user->hasPermissionTo($permission);
            //     });
            // });
        } catch (\Exception $e) {
            report($e);
            return false;
        }
//https://scotch.io/tutorials/all-about-writing-custom-blade-directives
        //Blade directives
        Blade::directive('role', function ($role) {
             return "if(auth()->check() && auth()->user()->hasRole({$role})) :"; //return this if statement inside php tag
        });

        Blade::directive('endrole', function ($role) {
             return "endif;"; //return this endif statement inside php tag
        });

        Blade::if('admin', function () {
            return auth()->check() && auth()->id() === 2340570;
        });
    }
}
