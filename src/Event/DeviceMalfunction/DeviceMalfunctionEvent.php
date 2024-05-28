<?php

declare(strict_types=1);

namespace App\Event\DeviceMalfunction;

use App\Event\AbstractEvent;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeviceMalfunctionEvent extends AbstractEvent
{
    public const EVENT_NAME = 'deviceMalfunction';

    public function __construct(
        string $deviceId,
        int $eventDate,
        #[Assert\NotBlank]
        #[Assert\Type(type: 'int')]
        public int $reasonCode,
        #[Assert\NotBlank]
        #[Assert\Type(type: 'string')]
        public string $reasonText
    ) {
        parent::__construct($deviceId, $eventDate, self::EVENT_NAME);
    }
}
