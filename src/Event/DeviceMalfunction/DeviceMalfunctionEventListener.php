<?php

declare(strict_types=1);

namespace App\Event\DeviceMalfunction;

use App\Command\SendEmail\SendEmailCommand;
use App\Command\SendLog\SendLogCommand;
use App\Command\SendSms\SendSmsCommand;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener]
final readonly class DeviceMalfunctionEventListener
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function __invoke(DeviceMalfunctionEvent $event): void
    {
        $this->sendLog($event);
        $this->sendEmail($event);
        $this->sendSms($event);
    }

    private function sendLog(DeviceMalfunctionEvent $event): void
    {
        $logCommand = new SendLogCommand(
            'Device ' . $event->deviceId . ' malfunction. Reason: ' . $event->reasonText
        );

        $this->messageBus->dispatch($logCommand);
    }

    private function sendEmail(DeviceMalfunctionEvent $event): void
    {
        $emailCommand = new SendEmailCommand(
            'test@localhost',
            'Device ' . $event->deviceId . ' malfunction',
            'Reason: ' . $event->reasonText
        );

        $this->messageBus->dispatch($emailCommand);
    }

    private function sendSms(DeviceMalfunctionEvent $event): void
    {
        $smsCommand = new SendSmsCommand(
            '123456789',
            'Device ' . $event->deviceId . ' malfunction. Reason: ' . $event->reasonText
        );

        $this->messageBus->dispatch($smsCommand);
    }
}
