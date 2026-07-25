<?php

namespace Coolycow\LaravelSms\Support;

use Coolycow\LaravelSms\Exceptions\SmsException;

final class PhoneNumber
{
    /**
     * Normalize a phone to digits-only E.164-like form (optional leading + stripped).
     *
     * @throws SmsException
     */
    public static function normalize(string $phone): string
    {
        $trimmed = trim($phone);

        if ($trimmed === '') {
            throw SmsException::forEmptyPhone();
        }

        $normalized = preg_replace('/[^\d]/', '', $trimmed) ?? '';

        if ($normalized === '' || strlen($normalized) < 8 || strlen($normalized) > 15) {
            throw SmsException::forInvalidPhone($phone);
        }

        return $normalized;
    }
}
