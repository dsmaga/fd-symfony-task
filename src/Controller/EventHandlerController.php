<?php

declare(strict_types=1);

namespace App\Controller;

use App\Event\AbstractEvent;
use App\Recorder\NotifyRecorder;
use App\Service\EventHandlerService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
final class EventHandlerController
{
    #[Route('/api/v1/event-handler', name: 'event_handler', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload(serializationContext: [AbstractNormalizer::REQUIRE_ALL_PROPERTIES => true])]
        AbstractEvent $event,
        EventHandlerService $eventHandlerService,
        NotifyRecorder $recorder
    ): Response {
        $eventHandlerService($event);
        return new JsonResponse([
            'status' => 'ok',
            'message' => $recorder->getMessages()
        ], Response::HTTP_OK);
    }
}
