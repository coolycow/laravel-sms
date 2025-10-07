<?php

namespace Coolycow\LaravelSms;

use Coolycow\LaravelSms\DTO\SmsDTO;
use Coolycow\LaravelSms\Enum\SmsStatusEnum;
use Coolycow\LaravelSms\Exceptions\SmsException;
use Coolycow\LaravelSms\Contracts\SmsClientInterface;
use Coolycow\LaravelSms\Contracts\SmsConfigInterface;
use Coolycow\LaravelSms\Contracts\SmsMessageInterface;
use Coolycow\LaravelSms\Contracts\SmsProviderInterface;

class SmsClient implements SmsClientInterface
{
    /**
     * См. NotificationServiceProvider
     *
     * @param SmsProviderInterface $provider
     * @param SmsMessageInterface $message
     * @param SmsConfigInterface $config
     */
    public function __construct(
        protected SmsProviderInterface $provider,
        protected SmsMessageInterface  $message,
        protected SmsConfigInterface   $config
    )
    {
        //
    }

    /**
     * @param string $phone
     * @param string $text
     * @param array $params
     * @return SmsMessageInterface
     * @throws SmsException
     */
    public function send(string $phone, string $text, array $params = []): SmsMessageInterface
    {
        // Пустой номер телефона - ошибка!
        if ($phone === '') {
            throw SmsException::forEmptyPhone();
        }

        // Пустое сообщение - ошибка!
        if ($text === '') {
            throw SmsException::forEmptyText();
        }

        // Создаем сообщение, которое надо отправить.
        $smsMessage = app(SmsMessageInterface::class)->createMessage(
            $phone,
            $this->getTextWithPrefix($text),
            $this->config->getSender(),
            $this->provider->getProviderCode(),
            $params
        );

        return $this->sendSms($smsMessage);
    }

    /**
     * @param  SmsDTO  $smsDTO
     * @return array
     * @throws SmsException
     */
    public function sendSmsDTO(SmsDTO $smsDTO): array
    {
        if (!$this->config->sendIsEnabled()) {
            throw SmsException::forSendIsDisabled();
        }

        return $this->provider->sendSms($smsDTO);
    }

    /**
     * @param  SmsMessageInterface  $smsMessage
     * @return SmsMessageInterface
     * @throws SmsException
     */
    public function sendSms(SmsMessageInterface $smsMessage): SmsMessageInterface
    {
        // Если отключена отправка СМС, то сохраняем сообщение в статусе блокировки.
        if (!$this->config->sendIsEnabled()) {
            // Записываем статус и искусственный ответ в сообщение, которое надо было отправить.
            $smsMessage->setStatus(SmsStatusEnum::DISABLED)->setResponse([
                'msg' => 'Отправка СМС отключена в настройках сервиса.'
            ])->saveMessage();

            return $smsMessage;
        }

        // Пытаемся отправить сообщение через указанного оператора.
        try {
            $response = $this->sendSmsDTO(
                new SmsDTO($smsMessage->phone, $smsMessage->text, $smsMessage->sender, $smsMessage->params)
            );
        } catch (SmsException $e) {
            $smsMessage->setErrorStatus($e->getMessage())->saveMessage();
            throw SmsException::forSend($e->getMessage());
        }

        // Анализируем ответ, выставляем статус сообщения и его id на стороне провайдера.
        $errorCode = $this->provider->getErrorCodeFromResponse($response);

        // Записываем статус в сообщение, которое надо было отправить.
        if ($errorCode !== 0) {
            $smsMessage->setStatus(
                SmsStatusEnum::tryFrom($errorCode) ?? SmsStatusEnum::ERROR,
                $this->provider->getErrorTextFromResponse($response)
            );
        } else {
            $smsMessage->setStatus(SmsStatusEnum::QUEUED)
                ->setProviderId($this->provider->getProviderIdFromResponse($response));
        }

        // Добавляем полный текст ответа в сообщение и сохраняем его.
        $smsMessage->setResponse($response)->saveMessage();

        return $smsMessage;
    }

    /**
     * @param  int  $providerId
     * @return array
     * @throws SmsException
     */
    public function getSmsStatus(int $providerId): array
    {
        return $this->provider->getStatus($providerId);
    }

    /**
     * Добавляет к тексту сообщения префикс.
     * В данном случае используется текст из настроек в админке.
     *
     * @param  string  $text
     * @return string
     */
    public function getTextWithPrefix(string $text): string
    {
        $prefix = $this->config->getPrefix();

        return (!empty($prefix) ? "$prefix " : '') . trim($text);
    }

    /**
     * Изменить провайдера.
     * Может пригодиться когда надо проверить статус старого сообщения, отправленного через другого провайдера.
     *
     * @param  SmsProviderInterface  $smsProvider
     * @return $this
     */
    public function setProvider(SmsProviderInterface $smsProvider): static
    {
        $this->provider = $smsProvider;

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | ОБЁРТКА НАД МЕТОДАМИ ПРОВАЙДЕРА
    |--------------------------------------------------------------------------
    */

    /**
     * Возвращает баланс в виде суммы на счете провайдера.
     *
     * @return float
     */
    public function getBalanceFloat(): float
    {
        return $this->provider->getBalanceFloat();
    }

    /**
     * Название провайдера.
     *
     * @return string
     */
    public function getProviderName(): string
    {
        return $this->provider->getProviderName();
    }

    /**
     * Код провайдера.
     *
     * @return string
     */
    public function getProviderCode(): string
    {
        return $this->provider->getProviderCode();
    }

    /**
     * Ссылку на личный кабинет провайдера.
     *
     * @return string
     */
    public function getPaymentUrl(): string
    {
        return $this->provider->getPaymentUrl();
    }

    /**
     * Получает из ответа код ошибки.
     * Если код === 0, то ошибки нет.
     *
     * @param  array  $response
     * @return int
     */
    public function getErrorCodeFromResponse(array $response): int
    {
        return $this->provider->getErrorCodeFromResponse($response);
    }

    /**
     * Получает из ответа текст ошибки.
     *
     * @param  array  $response
     * @return string
     */
    public function getErrorTextFromResponse(array $response): string
    {
        return $this->provider->getErrorTextFromResponse($response);
    }

    /**
     * Получает из ответа ID сообщения.
     *
     * @param  array  $response
     * @return int|null
     */
    public function getProviderIdFromResponse(array $response): int|null
    {
        return $this->provider->getProviderIdFromResponse($response);
    }

    /**
     * Получает из ответа проверки статуса сообщения цифровой код статуса.
     *
     * @param  array  $response
     * @return SmsStatusEnum
     */
    public function getStatusFromStatusResponse(array $response): SmsStatusEnum
    {
        return $this->provider->getStatusFromStatusResponse($response);
    }
}
