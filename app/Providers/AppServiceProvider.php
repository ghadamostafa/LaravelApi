<?php

namespace App\Providers;

use App\Mail\EmailChanged;
use App\Mail\UserCreated;
use App\Models\product;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        product::updated(function($product){
            if($product->quantity == 0 && $product->isAvailable())
            {
                $product->status=product::Unavailable_product;
                $product->save();
            }
        });

        User::created(function($user){
           retry(5,function() use($user) {
               Mail::to($user->email)->send(new UserCreated($user));
            },100);
        });

        User::updated(function($user){
            if($user->isDirty('email'))
            {
                 retry(5,function() use($user) {
                     Mail::to($user->email)->send(new EmailChanged($user));
                 },100);

            }
         });
    }
}
