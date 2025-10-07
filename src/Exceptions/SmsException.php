<?php

namespace Coolycow\LaravelSms\Exceptions;

use Coolycow\LaravelSms\Contracts\SmsExceptionInterface;
use Exception;

class SmsException extends Exception implements SmsExceptionInterface
{
    /**
     * @return static
     */
    public static function forEmptyAccessPoint(): static
    {
        return new static('Empty access point');
    }

    /**
     * @return static
     */
    public static function forEmptyApiLogin(): static
    {
        return new static('Empty api login');
    }

    /**
     * @return static
     */
    public static function forEmptyApiPassword(): static
    {
        return new static('Empty api password');
    }

    /**
     * @return static
     */
    public static function forEmptyResponse(): static
    {
        return new static('Empty response');
    }

    /**
     * @return static
     */
    public static function forCommunication(): static
    {
        return new static('Communication error');
    }

    /**
     * @return static
     */
    public static function forEmptyPhone(): static
    {
        return new static('Empty phone');
    }

    /**
     * @return static
     */
    public static function forEmptyText(): static
    {
        return new static('Empty text');
    }

    /**
     * @return static
     */
    public static function forSendIsDisabled(): static
    {
        return new static('Send is disabled');
    }

    /**
     * @param  string  $errorMessage
     * @return static
     */
    public static function forSend(string $errorMessage): static
    {
        return new static($errorMessage);
    }
}
