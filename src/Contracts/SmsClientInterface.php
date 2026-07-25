<?php

namespace Coolycow\LaravelSms\Contracts;

use Coolycow\LaravelSms\Exceptions\SmsException;

interface SmsClientInterface
{
    /**
     * @param  array<string, mixed>  $params
     *
     * @throws SmsException
     */
    public function send(string $phone, string $text, array $params = []): SmsMessageInterface;

    /**
     * @throws SmsException
     */
    public function sendSms(SmsMessageInterface $smsMessage): SmsMessageInterface;

    /**
     * @return array<string, mixed>
     *
     * @throws SmsException
     */
    public function getSmsStatus(int $providerId): array;

    public function getBalance(): float;

    public function getProviderName(): string;

    public function getProviderCode(): string;

    public function getPaymentUrl(): string;

    public function getTextWithPrefix(string $text): string;

    public function setProvider(SmsProviderInterface $smsProvider): static;
}
