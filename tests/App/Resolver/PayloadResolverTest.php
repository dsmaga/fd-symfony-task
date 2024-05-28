<?php

declare(strict_types=1);

namespace App\Tests\Resolver;

use App\Event\AbstractEvent;
use App\Event\DeviceMalfunction\DeviceMalfunctionEvent;
use App\Event\DoorUnlocked\DoorUnlockedEvent;
use App\Event\TemperatureExceeded\TemperatureExceededEvent;
use App\Resolver\PayloadResolver;
use App\Resolver\PayloadResolverException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PayloadResolverTest extends KernelTestCase
{
    private const JSON_PAYLOADS = [
        DeviceMalfunctionEvent::class => '{
            "deviceId": "A23",
            "eventDate": 1710355477,
            "type": "deviceMalfunction",
            "reasonCode": 12,
            "reasonText": "temp sensor not responding"
        }',
        TemperatureExceededEvent::class => '{
            "deviceId": "F12HJ",
            "eventDate": 1710353477,
            "type": "temperatureExceeded",
            "temp": 10.3,
            "threshold": 8.5
        }',
        DoorUnlockedEvent::class => '{
            "deviceId": "D12-1-12",
            "eventDate": 1710352477,
            "type": "doorUnlocked",
            "unlockDate": 1710350477
        }',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel([
            'environment' => 'test',
            'debug' => true,
        ]);
    }

    public function testResolvePayload(): void
    {
        /** @var PayloadResolver $payloadResolver */
        $payloadResolver = self::getContainer()->get(PayloadResolver::class);

        foreach (self::JSON_PAYLOADS as $eventClass => $payload) {
            $event = $payloadResolver->resolve($payload, AbstractEvent::class);

            self::assertInstanceOf($eventClass, $event);
        }
    }

    public function testResolvePayloadWithInvalidPayload(): void
    {
        $this->expectException(PayloadResolverException::class);

        /** @var PayloadResolver $payloadResolver */
        $payloadResolver = self::getContainer()->get(PayloadResolver::class);

        $payloadResolver->resolve('{"deviceId": "D12-1-12"}', AbstractEvent::class);
    }

    public function testResolvePayloadWithInvalidType(): void
    {
        $this->expectException(PayloadResolverException::class);

        /** @var PayloadResolver $payloadResolver */
        $payloadResolver = self::getContainer()->get(PayloadResolver::class);

        $payloadResolver->resolve('{"deviceId": "D12-1-12"}', PayloadResolver::class);
    }

    public function testResolveEmptyPayload(): void
    {
        $this->expectException(PayloadResolverException::class);

        /** @var PayloadResolver $payloadResolver */
        $payloadResolver = self::getContainer()->get(PayloadResolver::class);

        $payloadResolver->resolve('', AbstractEvent::class);
    }
}
