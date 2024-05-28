<?php

declare(strict_types=1);

namespace App\Event\TemperatureExceeded;

use App\Command\SendLog\SendLogCommand;
use App\Command\SendRequest\SendRequestCommand;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener]
final readonly class TemperatureExceededEventListener
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function __invoke(TemperatureExceededEvent $event): void
    {
        $this->sendLog($event);
        $this->sendWebhook($event);
    }

    private function sendLog(TemperatureExceededEvent $event): void
    {
        $message = sprintf(
            'Temperature exceeded. Device: %s, Temp: %s, Threshold: %s',
            $event->deviceId,
            $event->temp,
            $event->threshold
        );
        $logCommand = new SendLogCommand($message);

        $this->messageBus->dispatch($logCommand);
    }

    private function sendWebhook(TemperatureExceededEvent $event): void
    {
        $message = sprintf(
            'Temperature exceeded. Device: %s, Temp: %s, Threshold: %s',
            $event->deviceId,
            $event->temp,
            $event->threshold
        );
        $webhookCommand = new SendRequestCommand(
            'https://webhook.site/1b1b1b1b-1b1b-1b1b-1b1b-1b1b1b1b1b1b',
            $message
        );

        $this->messageBus->dispatch($webhookCommand);
    }
}
