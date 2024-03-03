<?php

namespace App\Service;

use App\DTO\LocationDto;
use App\Entity\Location;
use App\Repository\LocationRepository;
class LocationService
{
    private LocationRepository $locationRepository;
    private UrlGeneratorInterface $urlGenerator;
    public function __construct(LocationRepository $locationRepository,
                                UrlGeneratorInterface $urlGenerator)
    {
        $this->locationRepository = $locationRepository;
        $this->urlGenerator = $urlGenerator;
    }

    public function getLocations(int $page, int $perPage, string $sort, array $ids): array
    {
        $locations = $this->locationRepository->findPaginated($page, $perPage, $sort, $ids);

        $data = [];

        foreach ($locations as $location) {

            $characters = $location->getCharactersLocation()->toArray();
            $charactersUrls = $this->urlGenerator->generateUrls($characters, 'character');

            $data[] = [
                'id' => $location->getId(),
                'name' => $location->getName(),
                'type' => $location->getType(),
                'dimension' => $location->getDimension(),
                'residents' => $charactersUrls,
                'url' => $this->urlGenerator->getCurrentUrl($location->getId(), 'location'),
                'created' => $location->getCreated(),
            ];
        }

        return [
            'info' => [
                'count' => $this->locationRepository->getTotalEntityCount(),
                'page' => $page,
                'perPage' => $perPage,
            ],
            'results' => $data,
        ];
    }

    public function getLocation(int $locationId): array
    {
        $location = $this->locationRepository->find($locationId);

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


    public function createLocation(LocationDto $locationDto): Location
    {
        $location = new Location();
        $location->setName($locationDto->getName());
        $location->setType($locationDto->getType());
        $location->setDimension($locationDto->getDimension());
        $location->setCreated(new \DateTime());

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

}