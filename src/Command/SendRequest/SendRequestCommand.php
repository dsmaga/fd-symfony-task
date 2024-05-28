<?php

declare(strict_types=1);

namespace App\Command\SendRequest;

final readonly class SendRequestCommand
{
    public function __construct(
        public string $url,
        public string $message
    ) {
    }
}
