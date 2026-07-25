<?php

namespace Coolycow\LaravelSms\Tests;

use Coolycow\LaravelSms\Enum\SmsStatusEnum;
use Coolycow\LaravelSms\Services\SmsConfigService;
use PHPUnit\Framework\Attributes\Test;

class SmsConfigAndEnumTest extends TestCase
{
    #[Test]
    public function config_service_reads_sms_namespace(): void
    {
        config([
            'sms.enabled' => true,
            'sms.sender' => ' MyBrand ',
            'sms.prefix' => 'CODE',
            'sms.normal_balance' => 100,
            'sms.minimal_balance' => 10,
            'sms.default_provider' => 'fake',
        ]);

        $config = new SmsConfigService;

        $this->assertTrue($config->sendIsEnabled());
        $this->assertSame('MyBrand', $config->getSender());
        $this->assertSame('CODE', $config->getPrefix());
        $this->assertSame(100.0, $config->getNormalBalance());
        $this->assertSame(10.0, $config->getMinimalBalance());
        $this->assertSame('fake', $config->getDefaultProvider());
        $this->assertArrayHasKey('fake', $config->getAvailableProviders());
    }

    #[Test]
    public function enum_exposes_labels_and_intermediate_statuses(): void
    {
        $this->assertSame('В очереди', SmsStatusEnum::QUEUED->label());
        $this->assertTrue(SmsStatusEnum::QUEUED->isIntermediate());
        $this->assertTrue(SmsStatusEnum::DELIVERED->isFinal());
        $this->assertArrayHasKey('delivered', SmsStatusEnum::labels());
    }
}
