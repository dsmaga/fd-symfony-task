<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Event\DeviceMalfunction\DeviceMalfunctionEvent;
use App\Event\DoorUnlocked\DoorUnlockedEvent;
use App\Event\TemperatureExceeded\TemperatureExceededEvent;
use App\Recorder\NotifyRecorder;
use App\Service\EventHandlerService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EventHandlerServiceTest extends KernelTestCase
{
    protected EventHandlerService $eventHandlerService;
    protected NotifyRecorder $recorder;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel([
            'environment' => 'test',
            'debug' => true,
        ]);

        $container = self::getContainer();

        /** @var EventHandlerService $eventHandlerService */
        $eventHandlerService = $container->get(EventHandlerService::class);
        /** @var NotifyRecorder $recorder */
        $recorder = $container->get(NotifyRecorder::class);

        $this->eventHandlerService = $eventHandlerService;
        $this->recorder = $recorder;
    }

    /**
     * @return string[]
     */
    protected function filterRecorderByStartsWith(string $prefix): array
    {
        return array_filter($this->recorder->getMessages(), fn($message) => str_starts_with($message, $prefix));
    }

    public function testDeviceMalfunctionEventDispatching(): void
    {
        $event = new DeviceMalfunctionEvent('A23', 1710355477, 12, 'temp sensor not responding');

        $this->eventHandlerService->__invoke($event);

        self::assertCount(1, $this->filterRecorderByStartsWith('Email sent'));
        self::assertCount(1, $this->filterRecorderByStartsWith('SMS sent'));
        self::assertCount(1, $this->filterRecorderByStartsWith('Log sent'));
        self::assertCount(0, $this->filterRecorderByStartsWith('Request sent'));
    }

    public function testTemperatureExceededEventDispatching(): void
    {
        $event = new TemperatureExceededEvent('F12HJ', 1710353477, 10.3, 8.5);

        $this->eventHandlerService->__invoke($event);

        self::assertCount(0, $this->filterRecorderByStartsWith('Email sent'));
        self::assertCount(0, $this->filterRecorderByStartsWith('SMS sent'));
        self::assertCount(1, $this->filterRecorderByStartsWith('Log sent'));
        self::assertCount(1, $this->filterRecorderByStartsWith('Request sent'));
    }

    public function testDoorUnlockedEventDispatching(): void
    {
        $event = new DoorUnlockedEvent('D12-1-12', 1710352477, 1710350477);

        $this->eventHandlerService->__invoke($event);

        self::assertCount(0, $this->filterRecorderByStartsWith('Email sent'));
        self::assertCount(1, $this->filterRecorderByStartsWith('SMS sent'));
        self::assertCount(1, $this->filterRecorderByStartsWith('Log sent'));
        self::assertCount(0, $this->filterRecorderByStartsWith('Request sent'));
    }
}
