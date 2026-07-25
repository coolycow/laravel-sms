<?php

namespace Coolycow\LaravelSms\Contracts;

use Coolycow\LaravelSms\Enum\SmsStatusEnum;
use Illuminate\Support\Carbon;

interface SmsMessageInterface
{
    /**
     * @param  array<string, mixed>  $params
     */
    public function createMessage(
        string $phone,
        string $text,
        string $sender,
        string $providerCode,
        array $params = []
    ): static;

    public function getPhone(): string;

    public function getText(): string;

    public function getSender(): string;

    /**
     * @return array<string, mixed>
     */
    public function getParams(): array;

    public function getProvider(): string;

    public function getStatus(): SmsStatusEnum;

    public function getStatusText(): string;

    public function setStatus(SmsStatusEnum $status, ?string $statusText = null): static;

    /**
     * @param  array<string, mixed>  $response
     */
    public function setResponse(array $response): static;

    public function getProviderId(): ?int;

    public function setProviderId(?int $providerId): static;

    public function setErrorStatus(string $errorMessage = ''): static;

    /**
     * @param  array<string, mixed>  $options
     */
    public function saveMessage(array $options = []): bool;

    public function getCreatedAt(): Carbon;
}
