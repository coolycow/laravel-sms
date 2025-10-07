<?php

namespace Coolycow\LaravelSms\Enum;

enum SmsStatusEnum: string
{
    // Сообщение отправляется. Это промежуточный статус.
    case QUEUED = 'queued';

    // Сообщение доставлено в SMSC. Промежуточный статус.
    case SMSC_SUBMIT = 'sms_submit';

    // Сообщение доставлено абоненту. Это конечный статус.
    case DELIVERED = 'delivered';

    // Сообщение не доставлено абоненту. Это конечный статус.
    case NOT_DELIVERED = 'not_delivered';

    // Не доставлено в SMSC. Это конечный статус.
    case SMSC_DELIVERY_ERROR = 'smsc_delivery_error';

    // Сообщение отвергнуто SMSC (номер заблокирован или не существует). Это конечный статус.
    case SMSC_REJECT_ERROR = 'smsc_reject_error';

    // Сообщение не доставлено абоненту, срок "жизни" СМС истек. Это конечный статус.
    case DELIVERY_ERROR = 'delivery_error';

    // Ошибка в ответе. Это конечный статус.
    case RESPONSE_ERROR = 'response_error';

    // Неизвестная ошибка. Это конечный статус.
    case UNKNOWN_ERROR = 'unknown_error';

    // Проверка заблокирована. Это конечный статус.
    case DISABLED = 'disabled';

    // Проверка заблокирована. Это конечный статус.
    case BLOCKED = 'blocked';

    // Неизвестный ID. Это конечный статус.
    case UNKNOWN_ID = 'unknown_id';

    // Неизвестный статус. Это конечный статус.
    case UNKNOWN_STATUS = 'unknown_status';

    // Общая ошибка. Это конечный статус.
    case ERROR = 'error';

    // Неизвестный провайдер. Это конечный статус.
    case UNKNOWN_PROVIDER = 'unknown_provider';

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
