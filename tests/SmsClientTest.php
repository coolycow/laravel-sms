<?php

namespace Coolycow\LaravelSms\Tests;

use Coolycow\LaravelSms\Contracts\SmsClientInterface;
use Coolycow\LaravelSms\Contracts\SmsProviderInterface;
use Coolycow\LaravelSms\Enum\SmsStatusEnum;
use Coolycow\LaravelSms\Exceptions\SmsException;
use Coolycow\LaravelSms\Support\PhoneNumber;
use PHPUnit\Framework\Attributes\Test;

class SmsClientTest extends TestCase
{
    #[Test]
    public function it_sends_an_sms_and_persists_queued_status(): void
    {
        $client = $this->app->make(SmsClientInterface::class);

        $message = $client->send('+7 (999) 123-45-67', 'Hello world');

        $this->assertInstanceOf(FakeSmsMessage::class, $message);
        $this->assertTrue($message->saved);
        $this->assertSame('79991234567', $message->getPhone());
        $this->assertSame('Hello world', $message->getText());
        $this->assertSame(SmsStatusEnum::QUEUED, $message->getStatus());
        $this->assertSame(42, $message->getProviderId());
        $this->assertCount(1, FakeSmsMessage::$store);
    }

    #[Test]
    public function it_applies_prefix_from_config(): void
    {
        config(['sms.prefix' => 'APP']);

        $client = $this->app->make(SmsClientInterface::class);
        $message = $client->send('79991234567', 'Code 1234');

        $this->assertSame('APP Code 1234', $message->getText());
    }

    #[Test]
    public function it_persists_disabled_status_when_sending_is_off(): void
    {
        config(['sms.enabled' => false]);

        $client = $this->app->make(SmsClientInterface::class);
        $message = $client->send('79991234567', 'Hello');

        $this->assertSame(SmsStatusEnum::DISABLED, $message->getStatus());
        $this->assertNull($message->getProviderId());
        $this->assertTrue($message->saved);
        $this->assertCount(1, FakeSmsMessage::$store);
    }

    #[Test]
    public function it_persists_provider_error_status_without_throwing(): void
    {
        /** @var FakeSmsProvider $provider */
        $provider = $this->app->make(SmsProviderInterface::class);
        $provider->errorCode = 500;
        $provider->failMessage = 'Gateway rejected';
        $provider->sendFailureStatus = SmsStatusEnum::SMSC_REJECT_ERROR;

        $client = $this->app->make(SmsClientInterface::class);
        $message = $client->send('79991234567', 'Hello');

        $this->assertSame(SmsStatusEnum::SMSC_REJECT_ERROR, $message->getStatus());
        $this->assertSame('Gateway rejected', $message->getStatusText());
    }

    #[Test]
    public function it_marks_message_as_error_when_provider_throws(): void
    {
        /** @var FakeSmsProvider $provider */
        $provider = $this->app->make(SmsProviderInterface::class);
        $provider->shouldFail = true;
        $provider->failMessage = 'Connection lost';

        $client = $this->app->make(SmsClientInterface::class);

        try {
            $client->send('79991234567', 'Hello');
            $this->fail('Expected SmsException');
        } catch (SmsException $e) {
            $this->assertSame('Connection lost', $e->getMessage());
        }

        $this->assertCount(1, FakeSmsMessage::$store);
        $this->assertSame(SmsStatusEnum::ERROR, FakeSmsMessage::$store[0]->getStatus());
        $this->assertSame('Connection lost', FakeSmsMessage::$store[0]->getStatusText());
    }

    #[Test]
    public function it_rejects_empty_and_invalid_phones(): void
    {
        $client = $this->app->make(SmsClientInterface::class);

        $this->expectException(SmsException::class);
        $client->send('', 'Hello');
    }

    #[Test]
    public function it_rejects_empty_text(): void
    {
        $client = $this->app->make(SmsClientInterface::class);

        $this->expectException(SmsException::class);
        $client->send('79991234567', '   ');
    }

    #[Test]
    public function phone_number_normalizes_digits(): void
    {
        $this->assertSame('79991234567', PhoneNumber::normalize('+7 (999) 123-45-67'));
    }

    #[Test]
    public function it_exposes_provider_metadata(): void
    {
        $client = $this->app->make(SmsClientInterface::class);

        $this->assertSame('fake', $client->getProviderCode());
        $this->assertSame('Fake Provider', $client->getProviderName());
        $this->assertSame(1000.50, $client->getBalance());
        $this->assertSame('https://example.test/pay', $client->getPaymentUrl());
    }
}
