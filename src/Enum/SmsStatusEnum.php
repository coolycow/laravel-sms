<?php

namespace Coolycow\LaravelSms\Enum;

enum SmsStatusEnum: string
{
    case QUEUED = 'queued';

    case SMSC_SUBMIT = 'sms_submit';

    case DELIVERED = 'delivered';

    case NOT_DELIVERED = 'not_delivered';

    case SMSC_DELIVERY_ERROR = 'smsc_delivery_error';

    case SMSC_REJECT_ERROR = 'smsc_reject_error';

    case DELIVERY_ERROR = 'delivery_error';

    case RESPONSE_ERROR = 'response_error';

    case UNKNOWN_ERROR = 'unknown_error';

    case DISABLED = 'disabled';

    case BLOCKED = 'blocked';

    case UNKNOWN_ID = 'unknown_id';

    case UNKNOWN_STATUS = 'unknown_status';

    case ERROR = 'error';

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
     * @return array<string, string>
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
     * @return list<self>
     */
    public static function getIntermediateStatuses(): array
    {
        return [self::QUEUED, self::SMSC_SUBMIT];
    }

    public function isIntermediate(): bool
    {
        return in_array($this, self::getIntermediateStatuses(), true);
    }

    public function isFinal(): bool
    {
        return ! $this->isIntermediate();
    }
}
