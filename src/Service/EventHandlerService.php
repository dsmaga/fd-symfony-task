<?php

declare(strict_types=1);

namespace App\Service;

use App\Event\AbstractEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final readonly class EventHandlerService
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function __invoke(AbstractEvent $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }
}
