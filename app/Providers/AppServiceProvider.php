<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('user_data', Auth::user());
            }
            else{
                return view('auth.login');
            }


        });

        
    }
}
