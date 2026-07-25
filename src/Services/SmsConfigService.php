<?php

namespace Coolycow\LaravelSms\Services;

use Coolycow\LaravelSms\Contracts\SmsConfigInterface;

class SmsConfigService implements SmsConfigInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAvailableProviders(): array
    {
        /** @var array<string, class-string> $providers */
        $providers = config('sms.providers', []);

        return $providers;
    }

    public function getDefaultProvider(): ?string
    {
        $provider = config('sms.default_provider');

        return $provider === null || $provider === '' ? null : (string) $provider;
    }

    public function getSender(): string
    {
        return trim((string) config('sms.sender', 'Sender'));
    }

    public function getNormalBalance(): float
    {
        return (float) config('sms.normal_balance', 5000.0);
    }

    public function getMinimalBalance(): float
    {
        return (float) config('sms.minimal_balance', 300.0);
    }

    public function sendIsEnabled(): bool
    {
        return (bool) config('sms.enabled', false);
    }

    public function getPrefix(): string
    {
        $prefix = config('sms.prefix', '');

        return $prefix === null ? '' : (string) $prefix;
    }
}
