<?php

namespace Coolycow\LaravelSms\Exceptions;

use Exception;

class SmsException extends Exception
{
    public static function forEmptyAccessPoint(): self
    {
        return new self('Empty access point');
    }

    public static function forEmptyApiLogin(): self
    {
        return new self('Empty api login');
    }

    public static function forEmptyApiPassword(): self
    {
        return new self('Empty api password');
    }

    public static function forEmptyResponse(): self
    {
        return new self('Empty response');
    }

    public static function forCommunication(): self
    {
        return new self('Communication error');
    }

    public static function forEmptyPhone(): self
    {
        return new self('Empty phone');
    }

    public static function forInvalidPhone(string $phone): self
    {
        return new self("Invalid phone number: {$phone}");
    }

    public static function forEmptyText(): self
    {
        return new self('Empty text');
    }

    public static function forMissingProvider(?string $provider = null): self
    {
        $suffix = $provider ? ": {$provider}" : '';

        return new self("SMS provider is not configured{$suffix}");
    }

    public static function forSend(string $errorMessage): self
    {
        return new self($errorMessage);
    }
}
