<?php

declare(strict_types=1);

namespace App\Command\SendEmail;

final readonly class SendEmailCommand
{
    public function __construct(
        public string $recipient,
        public string $subject,
        public string $message
    ) {
    }
}
