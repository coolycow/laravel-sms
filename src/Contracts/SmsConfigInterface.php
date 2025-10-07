<?php

namespace Coolycow\LaravelSms\Contracts;

interface SmsConfigInterface
{
    /**
     * Возвращает массив доступных провайдеров.
     *
     * @return array
     */
    public function getAvailableProviders(): array;

    /**
     * Возвращает имя отправителя.
     *
     * @return string
     */
    public function getSender(): string;

    /**
     * Возвращает стандартную сумму, которая должна быть на счете провайдера.
     *
     * @return float
     */
    public function getNormalBalance(): float;

    /**
     * Возвращает минимальную сумму, которая должна быть на счете провайдера.
     *
     * @return float
     */
    public function getMinimalBalance(): float;

    /**
     * Нужно ли отправлять СМС.
     *
     * @return bool
     */
    public function sendIsEnabled(): bool;

    /**
     * Префикс для текста сообщения.
     *
     * @return string
     */
    public function getPrefix(): string;
}
