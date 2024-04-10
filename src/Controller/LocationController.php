<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\LocationDto;
use App\Service\LocationService;
use App\Service\PaginationService;
use App\Tools\QueryFilterInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LocationController extends AbstractController
{
    public function __construct(private LocationService $locationService,
        private QueryFilterInterface $queryFilter,
        private PaginationService $paginationService, )
    {
    }

    #[Route('/api/location', name: 'all.location', methods: 'GET')]
    public function getAllLocation(Request $request): JsonResponse
    {
        try {
            [$page, $limit] = $this->paginationService->getPageAndLimit($request);

            $allowedFilters = ['name', 'type', 'dimension'];

            $queries = $this->queryFilter->filter($request, $allowedFilters);

            $data = $this->locationService->getEntities($page, $limit, $queries);

            if (empty($data)) {
                return $this->json(['message' => 'no data available']);
            }

            return $this->json($data);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while fetching locations'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/locations/{ids}', name: 'location.by.ids', methods: 'GET')]
    public function getAllLocationByIds(string $ids, Request $request): JsonResponse
    {
        try {
            [$page, $limit] = $this->paginationService->getPageAndLimit($request);

            $data = $this->locationService->getEntitiesByIds($page, $limit, $ids);

            if (empty($data)) {
                return $this->json(['message' => 'nothing could be found on the request']);
            }

            return $this->json($data);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while fetching locations by ids'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/location/{id}', name: 'get.location', requirements: ['id' => '\d+'], methods: 'GET')]
    public function getLocation(int $id): JsonResponse
    {
        try {
            $data = $this->locationService->getEntity($id);

            if (empty($data)) {
                return $this->json(['message' => 'nothing could be found on the request']);
            }

            return $this->json(['result' => $data]);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while fetching location'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/location', name: 'create.location', methods: 'POST')]
    public function createLocation(LocationDto $locationDto): JsonResponse
    {
        try {
            $data = $this->locationService->createEntity($locationDto);

            return $this->json(['result' => $data], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while creating the location'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/location/{id}', name: 'put.update.location', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function putUpdateLocation(int $id, LocationDto $locationDto): JsonResponse
    {
        try {
            $data = $this->locationService->putUpdateEntity($id, $locationDto);

            if (empty($data)) {
                return $this->json(['message' => 'there is no such entity']);
            }

            return $this->json(['result' => $data]);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while updating the location'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/location/{id}', name: 'patch.update.location', requirements: ['id' => '\d+'], methods: ['PATCH'])]
    public function patchUpdateLocation(int $id, LocationDto $locationDto): JsonResponse
    {
        try {
            $data = $this->locationService->patchUpdateEntity($id, $locationDto);

            if (empty($data)) {
                return $this->json(['message' => 'there is no such entity']);
            }

            return $this->json(['result' => $data]);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while updating the location'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/location/{id}', name: 'delete.location', requirements: ['id' => '\d+'], methods: 'DELETE')]
    public function deleteLocation(int $id): JsonResponse
    {
        try {
            $isDelete = $this->locationService->deleteEntity($id);

            if (!$isDelete) {
                return $this->json(['message' => 'Unable to delete location'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->json(['message' => 'Location successfully deleted'], Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while deleting the location'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
