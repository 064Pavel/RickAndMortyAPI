<?php

declare(strict_types=1);

namespace App\Listener;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ValidationExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationException) {
            $responseData = [
                'error' => [
                    'message' => $exception->getMessage(),
                    'validation_errors' => $exception->getValidationErrors(),
                ],
            ];

            $response = new JsonResponse($responseData, Response::HTTP_BAD_REQUEST);

            $event->setResponse($response);
        }
    }
}

