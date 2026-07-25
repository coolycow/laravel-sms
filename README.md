# Laravel SMS

Laravel-пакет для отправки SMS через подключаемые провайдеры. Конкретные
реализации шлюзов живут в конечном приложении; этот пакет даёт клиент,
модель хранения, конфиг, фасад и канал уведомлений.

Требования: **PHP 8.2+** и **Laravel 12 / 13**.

## Установка

```shell
composer require coolycow/laravel-sms
```

Опубликуйте конфиг и миграции:

```shell
php artisan vendor:publish --tag=sms-config
php artisan vendor:publish --tag=sms-migrations
php artisan migrate
```

## Настройка

`config/sms.php`:

```php
return [
    'enabled' => env('SMS_ENABLED', false),
    'sender' => env('SMS_SENDER', 'Sender'),
    'prefix' => env('SMS_PREFIX', ''),
    'normal_balance' => env('SMS_NORMAL_BALANCE', 5000),
    'minimal_balance' => env('SMS_MINIMAL_BALANCE', 300),
    'default_provider' => env('SMS_PROVIDER', 'prosto'),
    'providers' => [
        'prosto' => App\Sms\Providers\ProstoProvider::class,
    ],
];
```

## Реализация провайдера

```php
<?php

namespace App\Sms\Providers;

use Coolycow\LaravelSms\Contracts\SmsProviderInterface;
use Coolycow\LaravelSms\DTO\SmsDTO;
use Coolycow\LaravelSms\Enum\SmsStatusEnum;

class ProstoProvider implements SmsProviderInterface
{
    public function sendSms(SmsDTO $dto): array
    {
        // Вызов SMS-шлюза; верните структурированный массив ответа.
        return [];
    }

    public function getStatus(int $id): array
    {
        return [];
    }

    public function getErrorCodeFromResponse(array $response): int
    {
        return (int) ($response['error'] ?? self::RESPONSE_ERROR_CODE);
    }

    public function getErrorTextFromResponse(array $response): string
    {
        return (string) ($response['error_text'] ?? self::RESPONSE_ERROR_TEXT);
    }

    public function getStatusFromSendResponse(array $response): SmsStatusEnum
    {
        return SmsStatusEnum::RESPONSE_ERROR;
    }

    public function getProviderIdFromResponse(array $response): ?int
    {
        return isset($response['id']) ? (int) $response['id'] : null;
    }

    public function getStatusFromStatusResponse(array $response): SmsStatusEnum
    {
        return SmsStatusEnum::UNKNOWN_STATUS;
    }

    public function getBalance(): float
    {
        return 0.0;
    }

    public function getProviderName(): string
    {
        return 'Prosto SMS';
    }

    public function getProviderCode(): string
    {
        return 'prosto';
    }

    public function getPaymentUrl(): string
    {
        return 'https://example.com';
    }
}
```

## Использование

```php
use Coolycow\LaravelSms\Facades\Sms;
use Coolycow\LaravelSms\Contracts\SmsClientInterface;

// Фасад
$message = Sms::send('79991234567', 'Ваш код: 1234');

// Контейнер
$message = app(SmsClientInterface::class)->send('79991234567', 'Привет');
```

Если `sms.enabled` равен `false`, сообщения сохраняются со статусом `disabled`
и не отправляются провайдеру.

### Laravel Notifications

```php
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    public function via(object $notifiable): array
    {
        return ['sms'];
    }

    public function toSms(object $notifiable): array
    {
        return [
            'text' => 'Ваш код: 1234',
            'params' => ['type' => 'otp'],
        ];
    }
}
```

Номер телефона берётся из `routeNotificationForSms()` у notifiable, либо из
атрибута `phone`, либо из ключа `phone` в ответе `toSms()`.

## Разработка

```shell
composer install
composer test
composer analyse
composer lint
```

## Лицензия

MIT
