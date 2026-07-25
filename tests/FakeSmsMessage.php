<?php

namespace Coolycow\LaravelSms\Tests;

use Coolycow\LaravelSms\Contracts\SmsMessageInterface;
use Coolycow\LaravelSms\Enum\SmsStatusEnum;
use Illuminate\Support\Carbon;

class FakeSmsMessage implements SmsMessageInterface
{
    public string $phone = '';

    public string $text = '';

    public string $sender = '';

    public string $provider = '';

    public ?int $providerId = null;

    public SmsStatusEnum $status = SmsStatusEnum::QUEUED;

    public string $statusText = '';

    /** @var array<string, mixed> */
    public array $params = [];

    /** @var array<string, mixed>|null */
    public ?array $response = null;

    public bool $saved = false;

    /** @var list<self> */
    public static array $store = [];

    public static function reset(): void
    {
        self::$store = [];
    }

    /**
     * {@inheritdoc}
     */
    public function createMessage(
        string $phone,
        string $text,
        string $sender,
        string $providerCode,
        array $params = []
    ): static {
        $message = new static;
        $message->phone = $phone;
        $message->text = $text;
        $message->sender = $sender;
        $message->provider = $providerCode;
        $message->params = $params;
        $message->status = SmsStatusEnum::QUEUED;
        $message->statusText = SmsStatusEnum::QUEUED->label();

        return $message;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    /**
     * {@inheritdoc}
     */
    public function getParams(): array
    {
        return $this->params;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getStatus(): SmsStatusEnum
    {
        return $this->status;
    }

    public function getStatusText(): string
    {
        return $this->statusText;
    }

    public function setStatus(SmsStatusEnum $status, ?string $statusText = null): static
    {
        $this->status = $status;
        $this->statusText = $statusText ?? $status->label();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setResponse(array $response): static
    {
        $this->response = $response;

        return $this;
    }

    public function getProviderId(): ?int
    {
        return $this->providerId;
    }

    public function setProviderId(?int $providerId): static
    {
        $this->providerId = $providerId;

        return $this;
    }

    public function setErrorStatus(string $errorMessage = ''): static
    {
        return $this->setStatus(
            SmsStatusEnum::ERROR,
            $errorMessage !== '' ? $errorMessage : SmsStatusEnum::ERROR->label()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function saveMessage(array $options = []): bool
    {
        $this->saved = true;
        self::$store[] = $this;

        return true;
    }

    public function getCreatedAt(): Carbon
    {
        return Carbon::now();
    }
}
