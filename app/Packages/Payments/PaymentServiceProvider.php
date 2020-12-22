<?php

namespace App\Packages\Payments;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Payment::class, function ($app){
            return new Payment();
        });

        $this->app->alias(Payment::class, 'payment');
    }

    public function provides()
    {
        return [Payment::class, 'payment'];
    }
}
