<?php

namespace App\Clients\Sms\Contracts;

use App\Clients\Sms\Enum\SmsStatusEnum;
use Carbon\Carbon;

interface SmsMessageInterface
{
    /**
     * Создание сообщения.
     *
     * @return $this
     */
    public function createMessage(
        int $phone,
        string $text,
        string $sender,
        string $providerCode,
        array $params = []
    ): static;

    /**
     * Получить код провайдера.
     *
     * @return string
     */
    public function getProvider(): string;

    /**
     * Получить статус сообщения.
     *
     * @return SmsStatusEnum
     */
    public function getStatusId(): SmsStatusEnum;

    /**
     * Установка статуса сообщения.
     *
     * @param  SmsStatusEnum  $status
     * @return $this
     */
    public function setStatusId(SmsStatusEnum $status): static;

    /**
     * Установить ответ провайдера.
     *
     * @param  array  $response
     * @return $this
     */
    public function setResponse(array $response): static;

    /**
     * Получить ID сообщения на стороне провайдера.
     *
     * @return int|null
     */
    public function getProviderId(): ?int;

    /**
     * Установить ID сообщения на стороне провайдера.
     *
     * @param  int|null  $providerId
     * @return $this
     */
    public function setProviderId(int|null $providerId): static;

    /**
     * Устанавливает статус ошибки.
     * Код ошибки будет установлен автоматически.
     * Текст ошибки можно передать.
     *
     * @param  string  $errorMessage // Текст ошибки.
     * @return $this
     */
    public function setErrorStatus(string $errorMessage = ''): static;

    /**
     * Сохранение сообщения.
     *
     * @param  array  $options
     * @return bool
     */
    public function saveMessage(array $options = []): bool;

    /**
     * Получить дату создания сообщения в UTC.
     *
     * @return Carbon
     */
    public function getCreatedAt(): Carbon;
}
