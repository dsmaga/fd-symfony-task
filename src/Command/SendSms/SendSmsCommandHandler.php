<?php

declare(strict_types=1);

namespace App\Command\SendSms;

use App\Recorder\NotifyRecorder;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SendSmsCommandHandler
{
    public function __construct(private NotifyRecorder $recorder)
    {
    }

    public function __invoke(SendSmsCommand $command): void
    {
        $message = sprintf(
            'SMS sent. Recipient: %s, Message: %s',
            $command->number,
            $command->message
        );
        $this->recorder->record($message);
    }
}
