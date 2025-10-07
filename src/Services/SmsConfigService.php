<?php

namespace App\Clients\Sms\Services;

use App\Clients\Sms\Contracts\SmsConfigInterface;

class SmsConfigService implements SmsConfigInterface
{
    /**
     * @return float
     */
    private function getDefaultNormalBalance(): float
    {
        return 5000.00;
    }

    /**
     * @return float
     */
    private function getDefaultMinimalBalance(): float
    {
        return 300.00;
    }

    /**
     * @return string
     */
    private function getDefaultSender(): string
    {
        return 'Sender';
    }

    /**
     * @return array
     */
    public function getAvailableProviders(): array
    {
        return config('sms.providers', []);
    }

    /**
     * Возвращает имя отправителя.
     *
     * @return string
     */
    public function getSender(): string
    {
        return trim(config('settings.sms_sender', $this->getDefaultSender()));
    }

    /**
     * Возвращает стандартную сумму, которая должна быть на счете провайдера.
     *
     * @return float
     */
    public function getNormalBalance(): float
    {
        return (float)config('settings.sms_normal_balance', $this->getDefaultNormalBalance());
    }

    /**
     * Возвращает минимальную сумму, которая должна быть на счете провайдера.
     *
     * @return float
     */
    public function getMinimalBalance(): float
    {
        return (float)config('settings.sms_minimal_balance', $this->getDefaultMinimalBalance());
    }

    /**
     * Нужно ли отправлять СМС.
     *
     * @return bool
     */
    public function sendIsEnabled(): bool
    {
        return (bool)config('settings.sms_send', false);
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return config('settings.sms_prefix', '');
    }
}
