<?php

namespace Coolycow\LaravelSms\Tests;

use Coolycow\LaravelSms\Contracts\SmsMessageInterface;
use Coolycow\LaravelSms\SmsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        FakeSmsMessage::reset();
    }

    protected function getPackageProviders($app): array
    {
        return [
            SmsServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('sms.enabled', true);
        $app['config']->set('sms.sender', 'TestSender');
        $app['config']->set('sms.prefix', '');
        $app['config']->set('sms.default_provider', 'fake');
        $app['config']->set('sms.providers', [
            'fake' => FakeSmsProvider::class,
        ]);

        $app->bind(SmsMessageInterface::class, FakeSmsMessage::class);
    }
}
