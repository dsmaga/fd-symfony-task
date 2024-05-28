<?php

declare(strict_types=1);

namespace App\Command\SendSms;

final readonly class SendSmsCommand
{
    public function __construct(
        public string $number,
        public string $message
    ) {
    }
}
