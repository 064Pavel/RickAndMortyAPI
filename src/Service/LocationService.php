<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\DtoInterface;
use App\Entity\EntityInterface;
use App\Repository\LocationRepository;
use App\Service\Factory\EntityFactory;
use App\Tools\PaginatorInterface;
use App\Tools\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

class LocationService implements ServiceInterface
{
    public function __construct(private LocationRepository $locationRepository,
        private UrlGeneratorInterface $urlGenerator,
        private PaginatorInterface $paginator,
        private EntityManagerInterface $entityManager,
        private EntityFactory $entityFactory, )
    {
    }

    public function getEntities(int $page, int $limit, array $queries = []): array
    {
        if (empty($queries)) {
            $locations = $this->locationRepository->findAll();
            $count = $this->locationRepository->getTotalEntityCount();
        } else {
            $locations = $this->locationRepository->findByFilters($queries);
            $count = $this->locationRepository->getTotalEntityCountWithFilters($queries);
        }

        $data = [];
        foreach ($locations as $location) {
            $data[] = $this->formatEntityData($location);
        }

        $options = [
            'page' => $page,
            'entityName' => 'location',
            'limit' => $limit,
            'query' => $queries,
        ];

        $data = $this->paginator->paginate($data, $options);
        $info = $this->paginator->formatInfo($data, $count, $options);

        return [
            'info' => $info,
            'results' => $data,
        ];
    }

    public function getEntitiesByIds(int $page, int $limit, string $ids): array
    {
        $locationIds = explode(',', $ids);

        $data = [];

        foreach ($locationIds as $id) {
            $character = $this->locationRepository->find($id);

            if (!$character) {
                continue;
            }

            $data[] = $this->formatEntityData($character);
        }

        $options = [
            'page' => $page,
            'entityName' => 'location',
            'limit' => $limit,
        ];

        $count = count($data);

        $data = $this->paginator->paginate($data, $options);
        $info = $this->paginator->formatInfo($data, $count, $options);

        return [
            'info' => $info,
            'results' => $data,
        ];
    }

    public function getEntity(int $id): ?array
    {
        $location = $this->locationRepository->find($id);
        if (!$location) {
            return null;
        }

        return $this->formatEntityData($location);
    }

    public function createEntity(DtoInterface $dto): array
    {
        $location = $this->entityFactory->createEntityFromDto($dto);

        $this->entityManager->persist($location);
        $this->entityManager->flush();

        return $this->formatEntityData($location);
    }

    public function putUpdateEntity(int $id, DtoInterface $dto): ?array
    {
        $location = $this->locationRepository->find($id);

        if (!$location) {
            return [];
        }

        $location = $this->entityFactory->putUpdateEntityFromDto($location, $dto);

        $this->entityManager->persist($location);
        $this->entityManager->flush();

        return $this->formatEntityData($location);
    }

    public function patchUpdateEntity(int $id, DtoInterface $dto): array
    {
        $location = $this->locationRepository->find($id);

        if (!$location) {
            return [];
        }

        $location = $this->entityFactory->patchUpdateEntityFromDto($location, $dto);

        $this->entityManager->persist($location);
        $this->entityManager->flush();

        return $this->formatEntityData($location);
    }

    public function deleteEntity(int $id): bool
    {
        $location = $this->locationRepository->find($id);

        if (!$location) {
            return false;
        }

        $this->entityManager->remove($location);
        $this->entityManager->flush();

        return true;
    }

    private function formatEntityData(EntityInterface $entity): array
    {
        $characters = $entity->getCharactersLocation()->toArray();
        $charactersUrls = $this->urlGenerator->generateUrls($characters, 'character');

        return [
            'id' => $entity->getId(),
            'name' => $entity->getName(),
            'type' => $entity->getType(),
            'dimension' => $entity->getDimension(),
            'residents' => $charactersUrls,
            'url' => $this->urlGenerator->getCurrentUrl($entity->getId(), 'entity'),
            'created' => $entity->getCreated(),
        ];
    }
}
