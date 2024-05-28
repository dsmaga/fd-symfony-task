<?php

declare(strict_types=1);

namespace App\Event\DoorUnlocked;

use App\Command\SendLog\SendLogCommand;
use App\Command\SendSms\SendSmsCommand;
use DateTimeImmutable;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener]
final readonly class DoorUnlockedEventListener
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function __invoke(DoorUnlockedEvent $event): void
    {
        $this->sendLog($event);
        $this->sendSms($event);
    }

    private function sendLog(DoorUnlockedEvent $event): void
    {
        $unlockDate = new DateTimeImmutable('@' . $event->unlockDate);
        $message = sprintf('Door %s unlocked at %s', $event->deviceId, $unlockDate->format('Y-m-d H:i:s'));
        $logCommand = new SendLogCommand($message);

        $this->messageBus->dispatch($logCommand);
    }

    private function sendSms(DoorUnlockedEvent $event): void
    {
        $unlockDate = new DateTimeImmutable('@' . $event->unlockDate);
        $message = sprintf('Door %s unlocked at %s', $event->deviceId, $unlockDate->format('Y-m-d H:i:s'));
        $smsCommand = new SendSmsCommand('123456789', $message);

        $this->messageBus->dispatch($smsCommand);
    }
}
