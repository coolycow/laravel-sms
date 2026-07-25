<?php

namespace Coolycow\LaravelSms\Channels;

use Coolycow\LaravelSms\Contracts\SmsClientInterface;
use Coolycow\LaravelSms\Contracts\SmsMessageInterface;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    public function __construct(
        protected SmsClientInterface $client,
    ) {}

    public function send(mixed $notifiable, Notification $notification): ?SmsMessageInterface
    {
        if (! method_exists($notification, 'toSms')) {
            return null;
        }

        /** @var array{phone?: string, text?: string, params?: array<string, mixed>}|string|null $message */
        $message = $notification->toSms($notifiable);

        if ($message === null) {
            return null;
        }

        if (is_string($message)) {
            $phone = $this->resolvePhone($notifiable);
            $text = $message;
            $params = [];
        } else {
            $phone = $message['phone'] ?? $this->resolvePhone($notifiable);
            $text = $message['text'] ?? '';
            $params = $message['params'] ?? [];
        }

        if ($phone === '' || $text === '') {
            return null;
        }

        return $this->client->send($phone, $text, $params);
    }

    protected function resolvePhone(mixed $notifiable): string
    {
        if (is_object($notifiable) && method_exists($notifiable, 'routeNotificationForSms')) {
            $phone = $notifiable->routeNotificationForSms();

            return is_string($phone) ? $phone : '';
        }

        if (is_object($notifiable) && isset($notifiable->phone) && is_string($notifiable->phone)) {
            return $notifiable->phone;
        }

        return '';
    }
}
