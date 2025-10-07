<?php

namespace App\Clients\Sms\Enum;

enum SmsStatusEnum: int
{
    // Сообщение отправляется. Это промежуточный статус.
    case QUEUED = -1;

    // Сообщение доставлено в SMSC. Промежуточный статус.
    case SMSC_SUBMIT = 0;

    // Сообщение доставлено абоненту. Это конечный статус.
    case DELIVERED = 1;

    // Сообщение не доставлено абоненту. Это конечный статус.
    case NOT_DELIVERED = 2;

    // Не доставлено в SMSC. Это конечный статус.
    case SMSC_DELIVERY_ERROR = 16;

    // Сообщение отвергнуто SMSC (номер заблокирован или не существует). Это конечный статус.
    case SMSC_REJECT_ERROR = 17;

    // Сообщение не доставлено абоненту, срок "жизни" СМС истек. Это конечный статус.
    case DELIVERY_ERROR = 34;

    // Ошибка в ответе. Это конечный статус.
    case RESPONSE_ERROR = 248;

    // Неизвестная ошибка. Это конечный статус.
    case UNKNOWN_ERROR = 249;

    // Проверка заблокирована. Это конечный статус.
    case DISABLED = 250;

    // Проверка заблокирована. Это конечный статус.
    case BLOCKED = 251;

    // Неизвестный ID. Это конечный статус.
    case UNKNOWN_ID = 252;

    // Неизвестный статус. Это конечный статус.
    case UNKNOWN_STATUS = 253;

    // Общая ошибка. Это конечный статус.
    case ERROR = 254;

    // Неизвестный провайдер. Это конечный статус.
    case UNKNOWN_PROVIDER = 255;

    public function label(): string
    {
        return match ($this) {
            self::QUEUED => 'В очереди',
            self::SMSC_SUBMIT => 'Сообщение доставлено в SMSC',
            self::DELIVERED => 'Доставлено',
            self::NOT_DELIVERED => 'Не доставлено',
            self::SMSC_DELIVERY_ERROR => 'Не доставлено в SMSC',
            self::SMSC_REJECT_ERROR => 'Сообщение отвергнуто SMSC',
            self::DELIVERY_ERROR => 'Просрочено',
            self::DISABLED => 'Отправка заблокирована',
            self::BLOCKED => 'Проверка заблокирована',
            self::UNKNOWN_ID => 'Неизвестный ID',
            self::UNKNOWN_STATUS => 'Неизвестный статус',
            self::ERROR => 'Общая ошибка',
            self::UNKNOWN_PROVIDER => 'Неизвестный провайдер',
            self::UNKNOWN_ERROR => 'Неизвестная ошибка',
            self::RESPONSE_ERROR => 'Ошибка в ответе',
        };
    }

    /**
     * @return string[]
     */
    public static function labels(): array
    {
        $labels = [];

        foreach (self::cases() as $status) {
            $labels[$status->value] = $status->label();
        }

        return $labels;
    }

    /**
     * Статусы, в которых нужно запрашивать статус сообщения.
     *
     * @return SmsStatusEnum[]
     */
    public static function getIntermediateStatuses(): array
    {
        return [self::SMSC_SUBMIT];
    }
}
