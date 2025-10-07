<?php

namespace Coolycow\LaravelSms\Contracts;

interface SmsExceptionInterface
{
    /**
     * @return static
     */
    public static function forEmptyAccessPoint(): static;

    /**
     * @return static
     */
    public static function forEmptyApiLogin(): static;

    /**
     * @return static
     */
    public static function forEmptyApiPassword(): static;

    /**
     * @return static
     */
    public static function forEmptyResponse(): static;

    /**
     * @return static
     */
    public static function forCommunication(): static;

    /**
     * @return static
     */
    public static function forEmptyPhone(): static;

    /**
     * @return static
     */
    public static function forEmptyText(): static;

    /**
     * @return static
     */
    public static function forSendIsDisabled(): static;

    /**
     * @param  string  $errorMessage
     * @return static
     */
    public static function forSend(string $errorMessage): static;
}
