<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\LocationDto;
use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Tools\PaginatorInterface;
use App\Tools\UrlGeneratorInterface;
use DateTime;

class LocationService
{
    private LocationRepository $locationRepository;
    private UrlGeneratorInterface $urlGenerator;
    private PaginatorInterface $paginator;

    public function __construct(LocationRepository $locationRepository,
        UrlGeneratorInterface $urlGenerator, PaginatorInterface $paginator)
    {
        $this->locationRepository = $locationRepository;
        $this->urlGenerator = $urlGenerator;
        $this->paginator = $paginator;
    }

    public function getLocations(int $page, int $limit, array $queries = []): array
    {
        if (empty($queries)) {
            $locations = $this->locationRepository->findAll();
        } else {
            $locations = $this->locationRepository->findByFilters($queries);
        }

        $data = [];
        foreach ($locations as $location) {
            $data[] = $this->formatLocationData($location);
        }

        $count = $this->locationRepository->getTotalEntityCount();

        $options = [
            'page' => $page,
            'entityName' => 'location',
            'limit' => $limit,
        ];

        $data = $this->paginator->paginate($data, $options);
        $info = $this->paginator->formatInfo($data, $count, $options);

        return [
            'info' => $info,
            'results' => $data,
            'q' => $queries,
        ];
    }

    public function getLocation(int $locationId): ?array
    {
        $location = $this->locationRepository->find($locationId);
        if (!$location) {
            return null;
        }

        return $this->formatLocationData($location);
    }

    public function createLocation(LocationDto $locationDto): Location
    {
        $location = new Location();
        $location->setName($locationDto->getName());
        $location->setType($locationDto->getType());
        $location->setDimension($locationDto->getDimension());
        $location->setCreated(new DateTime());

        $this->locationRepository->save($location);

        return $location;
    }

    public function updateLocation(int $id, LocationDto $locationDto): ?array
    {
        $location = $this->locationRepository->find($id);

        if (!$location) {
            return null;
        }

        $location->setName($locationDto->getName());
        $location->setType($locationDto->getType());
        $location->setDimension($locationDto->getDimension());

        $this->locationRepository->save($location);

        return [
            'id' => $location->getId(),
            'name' => $location->getName(),
            'type' => $location->getType(),
            'dimension' => $location->getDimension(),
        ];
    }

    public function deleteLocation(int $id): bool
    {
        $location = $this->locationRepository->find($id);

        if (!$location) {
            return false;
        }

        $this->locationRepository->remove($location);

        return true;
    }

    private function formatLocationData(Location $location): array
    {
        $characters = $location->getCharactersLocation()->toArray();
        $charactersUrls = $this->urlGenerator->generateUrls($characters, 'character');

        return [
            'id' => $location->getId(),
            'name' => $location->getName(),
            'type' => $location->getType(),
            'dimension' => $location->getDimension(),
            'residents' => $charactersUrls,
            'url' => $this->urlGenerator->getCurrentUrl($location->getId(), 'location'),
            'created' => $location->getCreated(),
        ];
    }
}
