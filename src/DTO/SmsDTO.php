<?php

namespace App\Clients\Sms\DTO;

/**
 * Используется в SmsClientInterface (метод sendSmsDTO) и в SmsProviderInterface (метод sendSms).
 */
class SmsDTO
{
    public function __construct(
        public string $phone,
        public string $text,
        public string $sender,
        public array $params = [],
    )
    {
        //
    }
}
