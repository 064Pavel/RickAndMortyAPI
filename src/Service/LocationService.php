<?php

namespace App\Service;

use App\DTO\LocationDto;
use App\Entity\Location;
use App\Repository\LocationRepository;
class LocationService
{
    private LocationRepository $locationRepository;
    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    public function getLocations(): array
    {
        $locations = $this->locationRepository->findAll();

        $data = [];

        foreach ($locations as $location) {
            $data[] = [
                'id' => $location->getId(),
                'name' => $location->getName(),
                'type' => $location->getType(),
                'dimension' => $location->getDimension(),
                'created' => $location->getCreated()->format('Y-m-d'),
            ];
        }

        return [
            'items' => $data,
            'meta' => [
                'total' => 0,
                'page' => 0,
                'perPage' => 0,
            ],
        ];
    }

    public function getLocation(int $locationId): array
    {
        $location = $this->locationRepository->find($locationId);

        return [
            'id' => $location->getId(),
            'name' => $location->getName(),
            'type' => $location->getType(),
            'dimension' => $location->getDimension(),
            'created' => $location->getCreated()->format('Y-m-d'),
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