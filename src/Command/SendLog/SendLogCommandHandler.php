<?php

declare(strict_types=1);

namespace App\Command\SendLog;

use App\Recorder\NotifyRecorder;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SendLogCommandHandler
{
    public function __construct(private NotifyRecorder $recorder)
    {
    }

    public function __invoke(SendLogCommand $command): void
    {
        $message = sprintf(
            'Log sent. Message: %s',
            $command->message
        );
        $this->recorder->record($message);
    }
}
