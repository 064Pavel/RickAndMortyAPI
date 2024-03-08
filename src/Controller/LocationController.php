<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\LocationDto;
use App\Service\LocationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LocationController extends AbstractController
{
    private LocationService $locationService;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(LocationService $locationService,
        SerializerInterface $serializer,
        ValidatorInterface $validator)
    {
        $this->locationService = $locationService;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('/api/location', name: 'all.location', methods: 'GET')]
    public function getAllLocation(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $data = $this->locationService->getLocations($page, $limit);

        if (!$data) {
            return $this->json([]);
        }

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/api/location/{id}', name: 'get.location', methods: 'GET')]
    public function getLocation(int $id): JsonResponse
    {
        $data = $this->locationService->getLocation($id);

        if (!$data) {
            return $this->json([]);
        }

        return $this->json(['result' => $data], Response::HTTP_OK);
    }

    #[Route('/api/location', name: 'create.location', methods: 'POST')]
    public function createLocation(Request $request): JsonResponse
    {
        $locationDto = $this->serializer->deserialize($request->getContent(), LocationDto::class, 'json');

        $errors = $this->validator->validate($locationDto);
        if (count($errors) > 0) {
            $errorsMessage = (string) $errors;

            return $this->json(['errors' => $errorsMessage], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->locationService->createLocation($locationDto);

        return $this->json(['result' => $data], Response::HTTP_CREATED);
    }

    #[Route('/api/location/{id}', name: 'update.location', methods: ['PUT', 'PATCH'])]
    public function updateLocation(int $id, Request $request): JsonResponse
    {
        $locationDto = $this->serializer->deserialize($request->getContent(), LocationDto::class, 'json');

        $errors = $this->validator->validate($locationDto);
        if (count($errors) > 0) {
            $errorsMessage = (string) $errors;

            return $this->json(['errors' => $errorsMessage], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->locationService->updateLocation($id, $locationDto);

        return $this->json(['result' => $data], Response::HTTP_OK);
    }

    #[Route('/api/location/{id}', name: 'delete.location', methods: 'DELETE')]
    public function deleteLocation(int $id): JsonResponse
    {
        $isDelete = $this->locationService->deleteLocation($id);

        if (!$isDelete) {
            return $this->json(['message' => 'failure', Response::HTTP_BAD_REQUEST]);
        }

        return $this->json(['message' => 'success']);
    }
}
