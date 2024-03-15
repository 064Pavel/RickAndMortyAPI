<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\LocationDto;
use App\Service\LocationService;
use App\Tools\DataConverterInterface;
use App\Tools\QueryFilterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LocationController extends AbstractController
{
    public function __construct(private LocationService $locationService,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private QueryFilterInterface $queryFilter,
        private DataConverterInterface $dataConverter)
    {
    }

    #[Route('/api/location', name: 'all.location', methods: 'GET')]
    public function getAllLocation(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $allowedFilters = ['name', 'type', 'dimension'];

        $queries = $this->queryFilter->filter($request, $allowedFilters);

        $data = $this->locationService->getLocations($page, $limit, $queries);

        if (empty($data)) {
            return $this->json(['message' => 'no data available']);
        }

        return $this->json($data);
    }

    #[Route('/api/locations/{ids}', name: 'all.location.by.ids', methods: 'GET')]
    public function getAllLocationByIds(string $ids): JsonResponse
    {
        $data = $this->locationService->getLocationByIds($ids);

        if (empty($data)) {
            return $this->json(['message' => 'nothing could be found on the request']);
        }

        return $this->json($data);
    }

    #[Route('/api/location/{id}', name: 'get.location', methods: 'GET')]
    public function getLocation(int $id): JsonResponse
    {
        $data = $this->locationService->getLocation($id);

        if (empty($data)) {
            return $this->json(['message' => 'nothing could be found on the request']);
        }

        return $this->json(['result' => $data]);
    }

    #[Route('/api/location', name: 'create.location', methods: 'POST')]
    public function createLocation(Request $request): JsonResponse
    {
        $locationDto = $this->processLocationDtoRequest($request);

        $errors = $this->validator->validate($locationDto);
        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json(['errors' => $errorsArray], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->locationService->createLocation($locationDto);

        if (empty($data)) {
            return $this->json(['message' => 'there is no such entity']);
        }

        return $this->json(['result' => $data], Response::HTTP_CREATED);
    }

    #[Route('/api/location/{id}', name: 'update.location', methods: ['PUT'])]
    public function updateLocation(int $id, Request $request): JsonResponse
    {
        $locationDto = $this->processLocationDtoRequest($request);

        $errors = $this->validator->validate($locationDto);
        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json(['errors' => $errorsArray], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->locationService->updateLocation($id, $locationDto);

        if (empty($data)) {
            return $this->json(['message' => 'there is no such entity']);
        }

        return $this->json(['result' => $data]);
    }

    #[Route('/api/location/{id}', name: 'patch.location', methods: ['PATCH'])]
    public function patchLocation(int $id, Request $request): JsonResponse
    {
        $locationDto = $this->processLocationDtoRequest($request);

        $errors = $this->validator->validate($locationDto, null, ['patch']);
        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json(['errors' => $errorsArray], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->locationService->patchLocation($id, $locationDto);

        if (empty($data)) {
            return $this->json(['message' => 'there is no such entity']);
        }

        return $this->json(['result' => $data]);
    }

    #[Route('/api/location/{id}', name: 'delete.location', methods: 'DELETE')]
    public function deleteLocation(int $id): JsonResponse
    {
        $isDelete = $this->locationService->deleteLocation($id);

        if (!$isDelete) {
            return $this->json(['message' => 'Unable to delete location'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json(['message' => 'Location successfully deleted']);
    }

    private function processLocationDtoRequest(Request $request): LocationDto
    {
        $convertFields = ['name', 'type', 'dimension'];

        $jsonData = $this->dataConvert($request, $convertFields);

        return $this->serializer->deserialize($jsonData, LocationDto::class, 'json');
    }

    private function dataConvert(Request $request, array $fieldsToConvert): string
    {
        $requestData = json_decode($request->getContent(), true);

        if (null === $requestData) {
            return '';
        }

        $this->dataConverter->convertToString($requestData, $fieldsToConvert);

        return json_encode($requestData);
    }
}
