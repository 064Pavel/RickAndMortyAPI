<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\LocationDto;
use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Tools\PaginatorInterface;
use App\Tools\UrlGeneratorInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class LocationService
{
    private PropertyAccessorInterface $propertyAccessor;

    public function __construct(private LocationRepository $locationRepository,
        private UrlGeneratorInterface $urlGenerator, private PaginatorInterface $paginator, private EntityManagerInterface $entityManager)
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
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
        ];
    }

    public function getLocationByIds(string $ids): array
    {
        $locationIds = explode(',', $ids);

        $data = [];

        foreach ($locationIds as $id) {
            $character = $this->locationRepository->find($id);

            if (!$character) {
                continue;
            }

            $data[] = $this->formatLocationData($character);
        }

        return $data;
    }

    public function getLocation(int $locationId): ?array
    {
        $location = $this->locationRepository->find($locationId);
        if (!$location) {
            return null;
        }

        return $this->formatLocationData($location);
    }

    public function createLocation(LocationDto $locationDto): array
    {
        $location = new Location();
        $location->setName($locationDto->getName());
        $location->setType($locationDto->getType());
        $location->setDimension($locationDto->getDimension());
        $location->setCreated(new DateTime());

        $this->locationRepository->save($location);

        return $this->formatLocationData($location);
    }

    public function updateLocation(int $id, LocationDto $locationDto): ?array
    {
        $location = $this->locationRepository->find($id);

        if (!$location) {
            return [];
        }

        $location->setName($locationDto->getName());
        $location->setType($locationDto->getType());
        $location->setDimension($locationDto->getDimension());

        $this->locationRepository->save($location);

        return $this->formatLocationData($location);
    }

    public function patchLocation(int $id, LocationDto $locationDto): array
    {
        $location = $this->entityManager->getRepository(Location::class)->find($id);

        if (!$location) {
            return [];
        }

        $location = $this->updateLocationFields($location, $locationDto);

        $this->locationRepository->save($location);

        return $this->formatLocationData($location);
    }

    private function updateLocationFields(Location $location, LocationDto $locationDto): Location
    {
        $reflectionClass = new ReflectionClass($locationDto);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $getterMethod = 'get' . ucfirst($propertyName);
            $setterMethod = 'set' . ucfirst($propertyName);

            if (method_exists($locationDto, $getterMethod) && method_exists($location, $setterMethod)) {
                $value = $locationDto->$getterMethod();

                if (null !== $value) {
                    $location->$setterMethod($value);
                }
            }
        }

        return $location;
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
