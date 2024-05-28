<?php

declare(strict_types=1);

namespace App\Command\SendLog;

final readonly class SendLogCommand
{
    public function __construct(
        public string $message
    ) {
    }
}
