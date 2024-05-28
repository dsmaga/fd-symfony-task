<?php

declare(strict_types=1);

namespace App\Recorder;

use Psr\Log\LoggerInterface;

class NotifyRecorder
{
    /**
     * @var string[]
     */
    private $messages = [];

    public function __construct(private LoggerInterface $notificatorLogger)
    {
    }

    public function record(string $message): void
    {
        $this->messages[] = $message;
        $this->notificatorLogger->info($message);
    }

    /**
     * @return string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
