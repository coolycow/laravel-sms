<?php

namespace Coolycow\LaravelSms\Tests;

use Coolycow\LaravelSms\Channels\SmsChannel;
use Coolycow\LaravelSms\Contracts\SmsClientInterface;
use Coolycow\LaravelSms\Enum\SmsStatusEnum;
use Illuminate\Notifications\Notification;
use PHPUnit\Framework\Attributes\Test;

class SmsChannelTest extends TestCase
{
    #[Test]
    public function it_sends_notification_via_sms_channel(): void
    {
        $notifiable = new class
        {
            public string $phone = '79991234567';

            public function routeNotificationForSms(): string
            {
                return $this->phone;
            }
        };

        $notification = new class extends Notification
        {
            public function via(object $notifiable): array
            {
                return ['sms'];
            }

            /**
             * @return array{text: string, params: array<string, mixed>}
             */
            public function toSms(object $notifiable): array
            {
                return [
                    'text' => 'Your code is 1234',
                    'params' => ['type' => 'otp'],
                ];
            }
        };

        /** @var SmsChannel $channel */
        $channel = $this->app->make(SmsChannel::class);
        $message = $channel->send($notifiable, $notification);

        $this->assertInstanceOf(FakeSmsMessage::class, $message);
        $this->assertSame(SmsStatusEnum::QUEUED, $message->getStatus());
        $this->assertSame('Your code is 1234', $message->getText());
        $this->assertSame(['type' => 'otp'], $message->getParams());
    }

    #[Test]
    public function it_accepts_string_payload_from_to_sms(): void
    {
        $notifiable = new class
        {
            public function routeNotificationForSms(): string
            {
                return '79991234567';
            }
        };

        $notification = new class extends Notification
        {
            public function toSms(object $notifiable): string
            {
                return 'Plain text SMS';
            }
        };

        $message = $this->app->make(SmsChannel::class)->send($notifiable, $notification);

        $this->assertNotNull($message);
        $this->assertSame('Plain text SMS', $message->getText());
    }

    #[Test]
    public function client_is_bound_in_container(): void
    {
        $this->assertInstanceOf(SmsClientInterface::class, $this->app->make('sms'));
    }
}
