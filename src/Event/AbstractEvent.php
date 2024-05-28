<?php

declare(strict_types=1);

namespace App\Event;

use App\Event\DeviceMalfunction\DeviceMalfunctionEvent;
use App\Event\DoorUnlocked\DoorUnlockedEvent;
use App\Event\TemperatureExceeded\TemperatureExceededEvent;
use Symfony\Component\Serializer\Attribute\DiscriminatorMap;
use Symfony\Component\Validator\Constraints as Assert;

#[DiscriminatorMap(typeProperty: 'type', mapping: [
    DeviceMalfunctionEvent::EVENT_NAME => DeviceMalfunctionEvent::class,
    TemperatureExceededEvent::EVENT_NAME => TemperatureExceededEvent::class,
    DoorUnlockedEvent::EVENT_NAME => DoorUnlockedEvent::class,
])]
abstract readonly class AbstractEvent
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type(type: 'string')]
        #[Assert\Regex(pattern: '/^[a-zA-Z0-9-]{1,36}$/')]
        public string $deviceId,
        #[Assert\NotBlank]
        #[Assert\Type(type: 'int')]
        #[Assert\Positive]
        public int $eventDate,
        #[Assert\NotBlank]
        public string $type
    ) {
    }
}
