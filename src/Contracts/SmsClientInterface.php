<?php

namespace App\Clients\Sms\Contracts;

use App\Clients\Sms\DTO\SmsDTO;
use App\Clients\Sms\Enum\SmsStatusEnum;
use App\Clients\Sms\Exceptions\SmsException;

interface SmsClientInterface
{
    /**
     * Отправить сообщение, указав телефон и текст сообщения.
     * Может выбросить исключение.
     * Например, если отправка отключена в настройках приложения.
     *
     * @param string $phone
     * @param string $text
     * @param array $params
     * @return SmsMessageInterface
     * @throws SmsException
     */
    public function send(string $phone, string $text, array $params = []): SmsMessageInterface;

    /**
     * Отправить сообщение, передав SmsDTO.
     * Может выбросить исключение.
     * Например, если отправка отключена в настройках приложения.
     *
     * @param  SmsDTO  $smsDTO
     * @return array
     * @throws SmsException
     */
    public function sendSmsDTO(SmsDTO $smsDTO): array;

    /**
     * Отправить сообщение, используя SmsMessageInterface.
     * Может выбросить исключение.
     * Например, если отправка отключена в настройках приложения.
     *
     * @param  SmsMessageInterface  $smsMessage
     * @return SmsMessageInterface
     * @throws SmsException
     */
    public function sendSms(SmsMessageInterface $smsMessage): SmsMessageInterface;

    /**
     * Статический метод запроса статуса сообщения.
     *
     * @param  int  $providerId
     * @return array
     * @throws SmsException
     */
    public function getSmsStatus(int $providerId): array;

    /**
     * Возвращает баланс в виде суммы на счете провайдера.
     *
     * @return float
     */
    public function getBalanceFloat(): float;

    /**
     * Название провайдера.
     *
     * @return string
     */
    public function getProviderName(): string;

    /**
     * Код провайдера.
     *
     * @return string
     */
    public function getProviderCode(): string;

    /**
     * Ссылку на личный кабинет провайдера.
     *
     * @return string
     */
    public function getPaymentUrl(): string;

    /**
     * Получает из ответа код ошибки.
     * Если код === 0, то ошибки нет.
     *
     * @param  array  $response
     * @return int
     */
    public function getErrorCodeFromResponse(array $response): int;

    /**
     * Получает из ответа текст ошибки.
     *
     * @param  array  $response
     * @return string
     */
    public function getErrorTextFromResponse(array $response): string;

    /**
     * Получает из ответа ID сообщения.
     *
     * @param  array  $response
     * @return int|null
     */
    public function getProviderIdFromResponse(array $response): int|null;

    /**
     * Получает из ответа проверки статуса сообщения цифровой код статуса.
     *
     * @param  array  $response
     * @return SmsStatusEnum
     */
    public function getStatusFromStatusResponse(array $response): SmsStatusEnum;

    /**
     * Добавляет к тексту сообщения префикс.
     *
     * @param  string  $text
     * @return string
     */
    public function getTextWithPrefix(string $text): string;

    /**
     * Изменить провайдера.
     *
     * @param  SmsProviderInterface  $smsProvider
     * @return static
     */
    public function setProvider(SmsProviderInterface $smsProvider): static;
}
