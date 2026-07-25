<?php

namespace Coolycow\LaravelSms\Facades;

use Coolycow\LaravelSms\Contracts\SmsClientInterface;
use Coolycow\LaravelSms\Contracts\SmsMessageInterface;
use Coolycow\LaravelSms\Contracts\SmsProviderInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static SmsMessageInterface send(string $phone, string $text, array $params = [])
 * @method static SmsMessageInterface sendSms(SmsMessageInterface $smsMessage)
 * @method static array getSmsStatus(int $providerId)
 * @method static float getBalance()
 * @method static string getProviderName()
 * @method static string getProviderCode()
 * @method static string getPaymentUrl()
 * @method static string getTextWithPrefix(string $text)
 * @method static SmsClientInterface setProvider(SmsProviderInterface $smsProvider)
 *
 * @see \Coolycow\LaravelSms\SmsClient
 */
class Sms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SmsClientInterface::class;
    }
}
