<?php

declare(strict_types=1);

namespace App\Controller;

use App\Event\AbstractEvent;
use App\Recorder\NotifyRecorder;
use App\Resolver\PayloadResolver;
use App\Service\EventHandlerService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class AlternativeEventHandlerController
{
    #[Route('/api/v1/alternative-event-handler', name: 'alternative_event_handler', methods: ['POST'])]
    public function __invoke(
        Request $request,
        PayloadResolver $payloadResolver,
        EventHandlerService $eventHandlerService,
        NotifyRecorder $recorder
    ): JsonResponse {
        /** @var AbstractEvent $event */
        $event = $payloadResolver->resolve($request->getContent(), AbstractEvent::class);
        $eventHandlerService($event);

        return new JsonResponse([
            'status' => 'ok',
            'message' => $recorder->getMessages()
        ]);
    }
}
