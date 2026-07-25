<?php

namespace Coolycow\LaravelSms\Models;

use Coolycow\LaravelSms\Contracts\SmsMessageInterface;
use Coolycow\LaravelSms\Enum\SmsStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $phone
 * @property string $text
 * @property string $sender
 * @property string $provider
 * @property int|null $provider_id
 * @property SmsStatusEnum $status
 * @property string $status_text
 * @property array<string, mixed> $params
 * @property array<string, mixed>|null $response
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class Sms extends Model implements SmsMessageInterface
{
    protected $table = 'sms';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'phone',
        'text',
        'sender',
        'provider',
        'provider_id',
        'status',
        'status_text',
        'params',
        'response',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'provider_id' => 'integer',
            'status' => SmsStatusEnum::class,
            'params' => 'array',
            'response' => 'array',
        ];
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
        $message = new self;
        $message->phone = $phone;
        $message->text = $text;
        $message->sender = $sender;
        $message->provider = $providerCode;
        $message->params = $params;
        $message->status = SmsStatusEnum::QUEUED;
        $message->status_text = SmsStatusEnum::QUEUED->label();

        return $message;
    }

    public function getPhone(): string
    {
        return (string) $this->phone;
    }

    public function getText(): string
    {
        return (string) $this->text;
    }

    public function getSender(): string
    {
        return (string) $this->sender;
    }

    /**
     * {@inheritdoc}
     */
    public function getParams(): array
    {
        return $this->params ?? [];
    }

    public function getProvider(): string
    {
        return (string) $this->provider;
    }

    public function getStatus(): SmsStatusEnum
    {
        return $this->status;
    }

    public function getStatusText(): string
    {
        return (string) $this->status_text;
    }

    public function setStatus(SmsStatusEnum $status, ?string $statusText = null): static
    {
        $this->status = $status;
        $this->status_text = $statusText ?? $status->label();

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
        return $this->provider_id;
    }

    public function setProviderId(?int $providerId): static
    {
        $this->provider_id = $providerId;

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
        return $this->save($options);
    }

    public function getCreatedAt(): Carbon
    {
        return Carbon::instance($this->created_at ?? now());
    }
}
