<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\DtoInterface;
use App\Entity\Episode;
use App\Repository\EpisodeRepository;
use App\Service\Factory\EntityFactory;
use App\Tools\PaginatorInterface;
use App\Tools\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

class EpisodeService implements ServiceInterface
{
    public function __construct(private EpisodeRepository $episodeRepository,
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $urlGenerator,
        private PaginatorInterface $paginator,
        private EntityFactory $entityFactory, )
    {
    }

    public function getEntities(int $page, int $limit, array $queries = []): array
    {
        if (empty($queries)) {
            $episodes = $this->episodeRepository->findAll();
            $count = $this->episodeRepository->getTotalEntityCount();
        } else {
            $episodes = $this->episodeRepository->findByFilters($queries);
            $count = $this->episodeRepository->getTotalEntityCountWithFilters($queries);
        }

        $data = [];
        foreach ($episodes as $episode) {
            $data[] = $this->formatEpisodeData($episode);
        }

        $options = [
            'page' => $page,
            'entityName' => 'episode',
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
        $episodesIds = explode(',', $ids);

        $data = [];

        foreach ($episodesIds as $id) {
            $character = $this->episodeRepository->find($id);

            if (!$character) {
                continue;
            }

            $data[] = $this->formatEpisodeData($character);
        }

        return $data;
    }

    public function getEntity(int $id): ?array
    {
        $episode = $this->episodeRepository->find($id);

        if (!$episode) {
            return null;
        }

        return $this->formatEpisodeData($episode);
    }

    public function createEntity(DtoInterface $dto): array
    {
        $episode = $this->entityFactory->createEntityFromDto($dto);

        $this->entityManager->persist($episode);
        $this->entityManager->flush();

        return $this->formatEpisodeData($episode);
    }

    public function putUpdateEntity(int $id, DtoInterface $dto): ?array
    {
        $episode = $this->episodeRepository->find($id);

        if (!$episode) {
            return [];
        }

        $episode = $this->entityFactory->putUpdateEntityFromDto($episode, $dto);

        $this->entityManager->persist($episode);
        $this->entityManager->flush();

        return $this->formatEpisodeData($episode);
    }

    public function patchUpdateEntity(int $id, DtoInterface $dto): array
    {
        $episode = $this->episodeRepository->find($id);

        if (!$episode) {
            return [];
        }

        $episode = $this->entityFactory->patchUpdateEntityFromDto($episode, $dto);

        $this->entityManager->persist($episode);
        $this->entityManager->flush();

        return $this->formatEpisodeData($episode);
    }

    public function deleteEntity(int $id): bool
    {
        $episode = $this->episodeRepository->find($id);

        if (!$episode) {
            return false;
        }

        $this->entityManager->remove($episode);
        $this->entityManager->flush();

        return true;
    }

    private function formatEpisodeData(Episode $episode): array
    {
        $characters = $episode->getCharacters()->toArray();
        $characterUrls = $this->urlGenerator->generateUrls($characters, 'character');

        return [
            'id' => $episode->getId(),
            'name' => $episode->getName(),
            'air_date' => $episode->getAirDate(),
            'episode' => $episode->getEpisode(),
            'characters' => $characterUrls,
            'views' => $episode->getViews(),
            'url' => $this->urlGenerator->getCurrentUrl($episode->getId(), 'episode'),
            'created' => $episode->getCreated(),
        ];
    }
}
