<?php

namespace App\Packages\OCR;

use Illuminate\Support\ServiceProvider;

class OCRServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ .'/config.php';

        $this->publishes([$configPath => config_path('ocr.php')], 'config');

        $this->mergeConfigFrom($configPath, 'ocr');

        $this->app->singleton(OCRService::class, function ($app) {
            return new OCRService($app->config->get('ocr'));
        });

        $this->app->alias(OCRService::class, 'ocr');
    }
}
