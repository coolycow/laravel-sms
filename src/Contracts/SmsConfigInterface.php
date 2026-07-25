<?php

namespace Coolycow\LaravelSms\Contracts;

interface SmsConfigInterface
{
    /**
     * @return array<string, class-string>
     */
    public function getAvailableProviders(): array;

    public function getDefaultProvider(): ?string;

    public function getSender(): string;

    public function getNormalBalance(): float;

    public function getMinimalBalance(): float;

    public function sendIsEnabled(): bool;

    public function getPrefix(): string;
}
