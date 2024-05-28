<?php

declare(strict_types=1);

namespace App\Event\TemperatureExceeded;

use App\Event\AbstractEvent;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class TemperatureExceededEvent extends AbstractEvent
{
    public const EVENT_NAME = 'temperatureExceeded';

    public function __construct(
        string $deviceId,
        int $eventDate,
        #[Assert\NotBlank]
        #[Assert\Type(type: 'float')]
        public float $temp,
        #[Assert\NotBlank]
        #[Assert\Type(type: 'float')]
        public float $threshold
    ) {
        parent::__construct($deviceId, $eventDate, self::EVENT_NAME);
    }
}
