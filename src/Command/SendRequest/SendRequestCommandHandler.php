<?php

declare(strict_types=1);

namespace App\Command\SendRequest;

use App\Recorder\NotifyRecorder;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SendRequestCommandHandler
{
    public function __construct(private NotifyRecorder $recorder)
    {
    }


    public function __invoke(SendRequestCommand $command): void
    {
        $message = sprintf(
            'Request sent. URL: %s, Message: %s',
            $command->url,
            $command->message
        );
        $this->recorder->record($message);
    }
}
