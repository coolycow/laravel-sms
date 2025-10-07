<?php

namespace Coolycow\LaravelSms;

use Coolycow\LaravelSms\Contracts\SmsConfigInterface;
use Coolycow\LaravelSms\Contracts\SmsClientInterface;
use Coolycow\LaravelSms\Contracts\SmsExceptionInterface;
use Coolycow\LaravelSms\Exceptions\SmsException;
use Coolycow\LaravelSms\Services\SmsConfigService;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    public array $bindings = [
        SmsClientInterface::class => SmsClient::class,
        SmsConfigInterface::class => SmsConfigService::class,
        SmsExceptionInterface::class => SmsException::class,
    ];

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/sms.php', 'sms'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/config/sms.php' => config_path('sms.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    }
}
