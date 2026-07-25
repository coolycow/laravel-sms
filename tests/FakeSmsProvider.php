<?php

namespace Coolycow\LaravelSms\Tests;

use Coolycow\LaravelSms\Contracts\SmsProviderInterface;
use Coolycow\LaravelSms\DTO\SmsDTO;
use Coolycow\LaravelSms\Enum\SmsStatusEnum;
use Coolycow\LaravelSms\Exceptions\SmsException;

class FakeSmsProvider implements SmsProviderInterface
{
    public bool $shouldFail = false;

    public string $failMessage = 'Provider failure';

    public int $errorCode = 0;

    public SmsStatusEnum $sendFailureStatus = SmsStatusEnum::RESPONSE_ERROR;

    /**
     * {@inheritdoc}
     */
    public function sendSms(SmsDTO $dto): array
    {
        if ($this->shouldFail) {
            throw SmsException::forSend($this->failMessage);
        }

        if ($this->errorCode !== 0) {
            return [
                'error' => $this->errorCode,
                'error_text' => $this->failMessage,
            ];
        }

        return [
            'error' => 0,
            'id' => 42,
            'phone' => $dto->phone,
            'text' => $dto->text,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus(int $id): array
    {
        return [
            'id' => $id,
            'status' => SmsStatusEnum::DELIVERED->value,
        ];
    }

    public function getErrorCodeFromResponse(array $response): int
    {
        return (int) ($response['error'] ?? self::RESPONSE_ERROR_CODE);
    }

    public function getErrorTextFromResponse(array $response): string
    {
        return (string) ($response['error_text'] ?? self::RESPONSE_ERROR_TEXT);
    }

    public function getStatusFromSendResponse(array $response): SmsStatusEnum
    {
        return $this->sendFailureStatus;
    }

    public function getProviderIdFromResponse(array $response): ?int
    {
        return isset($response['id']) ? (int) $response['id'] : null;
    }

    public function getStatusFromStatusResponse(array $response): SmsStatusEnum
    {
        return SmsStatusEnum::tryFrom((string) ($response['status'] ?? ''))
            ?? SmsStatusEnum::UNKNOWN_STATUS;
    }

    public function getBalance(): float
    {
        return 1000.50;
    }

    public function getProviderName(): string
    {
        return 'Fake Provider';
    }

    public function getProviderCode(): string
    {
        return 'fake';
    }

    public function getPaymentUrl(): string
    {
        return 'https://example.test/pay';
    }
}
