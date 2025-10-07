<?php

namespace App\Clients\Sms\Contracts;

use App\Clients\Sms\DTO\SmsDTO;
use App\Clients\Sms\Enum\SmsStatusEnum;
use App\Clients\Sms\Exceptions\SmsException;

/**
 * Данный интерфейс должны реализовывать все подключенные провайдеры SMS-сообщений.
 *
 * Для работы с реальной отправкой сообщения необходимо использовать интерфейс SmsClientInterface.
 */
interface SmsProviderInterface
{
    /**
     * Статический метод отправки СМС.
     *
     * @param  SmsDTO  $dto
     * @return array
     * @throws SmsException
     */
    public function sendSms(SmsDTO $dto): array;

    /**
     * Статический метод запроса статуса сообщения.
     *
     * @param  int  $id
     * @return array
     * @throws SmsException
     */
    public function getStatus(int $id): array;

    /**
     * Код ошибки запроса.
     * Должен вернуть 0 в случае, если запрос прошел без ошибок.
     * В случае ошибки должен вернуть цифровой код ошибки провайдера или же значение RESPONSE_ERROR_CODE.
     *
     * @param  array  $response
     * @return int
     */
    public function getErrorCodeFromResponse(array $response): int;

    /**
     * Текст ошибки запроса.
     * Должен вернуть текст ошибки провайдера или же значение RESPONSE_ERROR_TEXT.
     *
     * @param  array  $response
     * @return string
     */
    public function getErrorTextFromResponse(array $response): string;

    /**
     * ID сообщение у провайдера.
     * Должен вернуть цифровой ID сообщение или же NULL.
     *
     * @param  array  $response
     * @return int|null
     */
    public function getProviderIdFromResponse(array $response): int|null;

    /**
     * Цифровой статус сообщения из запроса статуса.
     *
     *
     * @param  array  $response
     * @return SmsStatusEnum
     */
    public function getStatusFromStatusResponse(array $response): SmsStatusEnum;

    /**
     * Статический метод запроса баланса.
     *
     * @return array|string
     */
    public function getBalance(): array|string;

    /**
     * Баланс для виджета в админке. Не используется.
     *
     * @return string
     */
    public function getBalanceString(): string;

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
}
