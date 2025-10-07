# Laravel SMS
Пакет для Laravel, позволяющий отправлять SMS-сообщение, используя несколько провайдеров.

## Установка
```shell
composer require coolycow/laravel-sms
```
Далее:
```shell
php artisan vendor:publish --provider="Coolycow\LaravelSms\SmsServiceProvider" --tag=config
```

## Настройка
Необходимо указать конкретный класс, который отвечает за SMS, а также привязать провайдеров SMS.  
Создаём свой Service Provider со следующим содержимым (название может быть другим):
```php
namespace App\Providers;

use App\Clients\Sms\Contracts\SmsMessageInterface;
use App\Clients\Sms\Contracts\SmsProviderInterface;
use App\Clients\Sms\Providers\SmsDiscountProvider;
use App\Clients\Sms\Providers\SmsProstoProvider;
use App\Models\Sms;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        // Переопределяем биндинг интерфейса SmsMessageInterface.
        $this->app->bind(SmsMessageInterface::class, Sms::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        // В зависимости от конфига биндим провайдеры.
        if (config('settings.sms_provider', 'prosto') === 'prosto') {
            $this->app->bind(SmsProviderInterface::class, SmsProstoProvider::class);
        } else {
            $this->app->bind(SmsProviderInterface::class, SmsDiscountProvider::class);
        }
    }
}
```

Соответственно модель `App\Models\Sms` должна реализовывать `SmsMessageInterface`, а провайдеры SMS - `SmsProviderInterface`.
В примере выше используется два провайдера, которые подключаются в зависимости от настроек основного приложения.