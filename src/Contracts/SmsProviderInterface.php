<?php

namespace Coolycow\LaravelSms\Contracts;

use Coolycow\LaravelSms\DTO\SmsDTO;
use Coolycow\LaravelSms\Enum\SmsStatusEnum;
use Coolycow\LaravelSms\Exceptions\SmsException;

/**
 * Application-side SMS gateway adapters must implement this interface.
 *
 * Use SmsClientInterface for sending; do not call providers directly from app code.
 */
interface SmsProviderInterface
{
    public const RESPONSE_ERROR_CODE = -1;

    public const RESPONSE_ERROR_TEXT = 'Invalid provider response';

    /**
     * @return array<string, mixed>
     *
     * @throws SmsException
     */
    public function sendSms(SmsDTO $dto): array;

    /**
     * @return array<string, mixed>
     *
     * @throws SmsException
     */
    public function getStatus(int $id): array;

    /**
     * Return 0 when the send request succeeded.
     * On failure return the provider error code or RESPONSE_ERROR_CODE.
     *
     * @param  array<string, mixed>  $response
     */
    public function getErrorCodeFromResponse(array $response): int;

    /**
     * @param  array<string, mixed>  $response
     */
    public function getErrorTextFromResponse(array $response): string;

    /**
     * Map a failed send response to a package status.
     *
     * @param  array<string, mixed>  $response
     */
    public function getStatusFromSendResponse(array $response): SmsStatusEnum;

    /**
     * @param  array<string, mixed>  $response
     */
    public function getProviderIdFromResponse(array $response): ?int;

    /**
     * @param  array<string, mixed>  $response
     */
    public function getStatusFromStatusResponse(array $response): SmsStatusEnum;

    public function getBalance(): float;

    public function getProviderName(): string;

    public function getProviderCode(): string;

    public function getPaymentUrl(): string;
}
