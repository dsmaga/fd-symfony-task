<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Resolver\PayloadResolverException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Throwable;

#[AsEventListener]
final readonly class ExceptionEventListener
{
    public function __construct(
        private KernelInterface $kernel
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        list($statusCode, $responseData) = $this->createResponse($exception);

        if ($this->kernel->getEnvironment() === 'dev') {
            $debug = [
                'exception' => [
                    'class' => get_class($exception),
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTrace()
                ]
            ];
            $responseData['_debug'] = $debug;
        }

        $response = new JsonResponse($responseData, $statusCode);

        $event->setResponse($response);
    }

    /**
     * @return array{0: int, 1: array<string, mixed>}
     */
    private function createResponse(Throwable $exception): array
    {
        if ($exception instanceof HttpException) {
            return $this->onHttpException($exception);
        }

        if ($exception instanceof PayloadResolverException) {
            return $this->onPayloadResolverException($exception);
        }

        return $this->onUnexpectedException($exception);
    }


    /**
     * @return array{0: int, 1: array<string, mixed>}
     */
    private function onUnexpectedException(Throwable $exception): array
    {
        return [
           Response::HTTP_INTERNAL_SERVER_ERROR,
           [
               'message' => 'Unexpected error occurred',
           ]
        ];
    }

    /**
     * @return array{0: int, 1: array<string, mixed>}
     */
    private function onHttpException(HttpException $exception): array
    {
        $message = $exception->getMessage();

        if (json_validate($message)) {
            $message = (array)json_decode($message, true);
        } else {
            $message = ['message' => $message];
        }

        return [
            $exception->getStatusCode(),
            $message
        ];
    }

    /**
     * @return array{0: int, 1: array<string, mixed>}
     */
    private function onPayloadResolverException(PayloadResolverException $exception): array
    {
        return [
            Response::HTTP_UNPROCESSABLE_ENTITY,
            [
                'message' => 'Validation failed',
                'errors' => $exception->getErrors()
            ]
        ];
    }
}
