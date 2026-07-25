<?php

namespace Coolycow\LaravelSms;

use Coolycow\LaravelSms\Channels\SmsChannel;
use Coolycow\LaravelSms\Contracts\SmsClientInterface;
use Coolycow\LaravelSms\Contracts\SmsConfigInterface;
use Coolycow\LaravelSms\Contracts\SmsMessageInterface;
use Coolycow\LaravelSms\Contracts\SmsProviderInterface;
use Coolycow\LaravelSms\Exceptions\SmsException;
use Coolycow\LaravelSms\Models\Sms;
use Coolycow\LaravelSms\Services\SmsConfigService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/Config/sms.php', 'sms');

        $this->app->singleton(SmsConfigInterface::class, SmsConfigService::class);

        $this->app->bind(SmsMessageInterface::class, Sms::class);

        $this->app->singleton(SmsProviderInterface::class, function (Application $app): SmsProviderInterface {
            /** @var SmsConfigInterface $config */
            $config = $app->make(SmsConfigInterface::class);
            $code = $config->getDefaultProvider();
            $providers = $config->getAvailableProviders();

            if ($code === null || ! isset($providers[$code])) {
                throw SmsException::forMissingProvider($code);
            }

            $provider = $app->make($providers[$code]);

            if (! $provider instanceof SmsProviderInterface) {
                throw SmsException::forMissingProvider($code);
            }

            return $provider;
        });

        $this->app->bind(SmsClientInterface::class, SmsClient::class);
        $this->app->alias(SmsClientInterface::class, 'sms');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/Config/sms.php' => config_path('sms.php'),
            ], 'sms-config');

            $this->publishes([
                __DIR__.'/../database/migrations/2024_01_01_000001_create_sms_table.php' => database_path('migrations/2024_01_01_000001_create_sms_table.php'),
            ], 'sms-migrations');
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->app->resolving(ChannelManager::class, function (ChannelManager $channels, Application $app): void {
            $channels->extend('sms', function () use ($app): SmsChannel {
                return $app->make(SmsChannel::class);
            });
        });
    }
}
