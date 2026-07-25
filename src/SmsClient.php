<?php

namespace Coolycow\LaravelSms;

use Coolycow\LaravelSms\Contracts\SmsClientInterface;
use Coolycow\LaravelSms\Contracts\SmsConfigInterface;
use Coolycow\LaravelSms\Contracts\SmsMessageInterface;
use Coolycow\LaravelSms\Contracts\SmsProviderInterface;
use Coolycow\LaravelSms\DTO\SmsDTO;
use Coolycow\LaravelSms\Enum\SmsStatusEnum;
use Coolycow\LaravelSms\Exceptions\SmsException;
use Coolycow\LaravelSms\Support\PhoneNumber;

class SmsClient implements SmsClientInterface
{
    public function __construct(
        protected SmsProviderInterface $provider,
        protected SmsMessageInterface $message,
        protected SmsConfigInterface $config,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function send(string $phone, string $text, array $params = []): SmsMessageInterface
    {
        $phone = PhoneNumber::normalize($phone);
        $text = trim($text);

        if ($text === '') {
            throw SmsException::forEmptyText();
        }

        $smsMessage = $this->message->createMessage(
            $phone,
            $this->getTextWithPrefix($text),
            $this->config->getSender(),
            $this->provider->getProviderCode(),
            $params
        );

        return $this->sendSms($smsMessage);
    }

    /**
     * {@inheritdoc}
     */
    public function sendSms(SmsMessageInterface $smsMessage): SmsMessageInterface
    {
        if (! $this->config->sendIsEnabled()) {
            $smsMessage->setStatus(SmsStatusEnum::DISABLED, 'SMS sending is disabled in package configuration.')
                ->setResponse(['msg' => 'SMS sending is disabled in package configuration.'])
                ->saveMessage();

            return $smsMessage;
        }

        try {
            $response = $this->provider->sendSms(
                new SmsDTO(
                    $smsMessage->getPhone(),
                    $smsMessage->getText(),
                    $smsMessage->getSender(),
                    $smsMessage->getParams(),
                )
            );
        } catch (SmsException $e) {
            $smsMessage->setErrorStatus($e->getMessage())->saveMessage();

            throw SmsException::forSend($e->getMessage());
        }

        $errorCode = $this->provider->getErrorCodeFromResponse($response);

        if ($errorCode !== 0) {
            $smsMessage->setStatus(
                $this->provider->getStatusFromSendResponse($response),
                $this->provider->getErrorTextFromResponse($response)
            );
        } else {
            $smsMessage->setStatus(SmsStatusEnum::QUEUED)
                ->setProviderId($this->provider->getProviderIdFromResponse($response));
        }

        $smsMessage->setResponse($response)->saveMessage();

        return $smsMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function getSmsStatus(int $providerId): array
    {
        return $this->provider->getStatus($providerId);
    }

    public function getTextWithPrefix(string $text): string
    {
        $prefix = $this->config->getPrefix();

        return ($prefix !== '' ? "{$prefix} " : '').trim($text);
    }

    public function setProvider(SmsProviderInterface $smsProvider): static
    {
        $this->provider = $smsProvider;

        return $this;
    }

    public function getBalance(): float
    {
        return $this->provider->getBalance();
    }

    public function getProviderName(): string
    {
        return $this->provider->getProviderName();
    }

    public function getProviderCode(): string
    {
        return $this->provider->getProviderCode();
    }

    public function getPaymentUrl(): string
    {
        return $this->provider->getPaymentUrl();
    }
}
