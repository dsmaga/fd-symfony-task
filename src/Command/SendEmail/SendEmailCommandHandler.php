<?php

declare(strict_types=1);

namespace App\Command\SendEmail;

use App\Recorder\NotifyRecorder;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SendEmailCommandHandler
{
    public function __construct(private NotifyRecorder $recorder)
    {
    }

    public function __invoke(SendEmailCommand $command): void
    {
        $message = sprintf(
            'Email sent. Recipient: %s, Subject: %s, Message: %s',
            $command->recipient,
            $command->subject,
            $command->message
        );
        $this->recorder->record($message);
    }
}
