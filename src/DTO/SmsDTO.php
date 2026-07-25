<?php

namespace Coolycow\LaravelSms\DTO;

readonly class SmsDTO
{
    /**
     * @param  array<string, mixed>  $params
     */
    public function __construct(
        public string $phone,
        public string $text,
        public string $sender,
        public array $params = [],
    ) {}
}
