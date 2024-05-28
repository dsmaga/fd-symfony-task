<?php

declare(strict_types=1);

namespace App\Event\DoorUnlocked;

use App\Event\AbstractEvent;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class DoorUnlockedEvent extends AbstractEvent
{
    public const EVENT_NAME = 'doorUnlocked';

    public function __construct(
        string $deviceId,
        int $eventDate,
        #[Assert\NotBlank]
        #[Assert\Type(type: 'int')]
        #[Assert\Positive]
        public int $unlockDate
    ) {
        parent::__construct($deviceId, $eventDate, self::EVENT_NAME);
    }
}
