# Changelog

## 2.0.0

Breaking-переписывание под Laravel 12/13 и PHP 8.2+.

- Самодостаточный конфиг `sms.*` (без привязки к `settings.*`).
- Eloquent-модель `Sms` по умолчанию, реализующая `SmsMessageInterface`.
- Исправлены несовпадения контрактов (тип телефона, `setStatus`, геттеры).
- Маппинг статусов через провайдер: `getStatusFromSendResponse()`.
- Единая семантика отключённой отправки (сохраняется `DISABLED`, без исключения).
- Из `SmsClientInterface` убраны методы разбора ответа провайдера.
- API баланса провайдера упрощён до `getBalance(): float`.
- Добавлены фасад `Sms` и канал Laravel Notification `sms`.
- Добавлены тесты, PHPStan, Pint и GitHub Actions CI.
- Поддержка Laravel 13 и PHP 8.5.
- Laravel 11 убран: security support закончился, Packagist блокирует уязвимые `illuminate/*` 11.x.
