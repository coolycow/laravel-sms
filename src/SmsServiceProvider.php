<?php

namespace App\Clients\Sms\Providers;

use App\Clients\Sms\Contracts\SmsClientInterface;
use App\Clients\Sms\Contracts\SmsConfigInterface;
use App\Clients\Sms\Contracts\SmsExceptionInterface;
use App\Clients\Sms\Exceptions\SmsException;
use App\Clients\Sms\Services\SmsConfigService;
use App\Clients\Sms\SmsClient;
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
