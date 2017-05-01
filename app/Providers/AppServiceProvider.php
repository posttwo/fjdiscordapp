<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Auth;
use App\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Blade::directive('break', function () {
            return "<?php break; ?>";
        });
        Blade::directive('ifgroup', function($name) {
            return  "<?php if(Auth::check() && Auth::user()->roles()->remember(1440)->cacheTags('role_membership.' . Auth::user()->id . '.$name')->where('slug', '$name')->first()): ?>";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
