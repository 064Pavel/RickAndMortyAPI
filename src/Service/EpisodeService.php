<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\EpisodeDto;
use App\Entity\Episode;
use App\Repository\EpisodeRepository;
use App\Tools\PaginatorInterface;
use App\Tools\UrlGeneratorInterface;
use DateTime;

class EpisodeService
{
    private EpisodeRepository $episodeRepository;
    private UrlGeneratorInterface $urlGenerator;
    private PaginatorInterface $paginator;

    public function __construct(EpisodeRepository $episodeRepository,
        UrlGeneratorInterface $urlGenerator, PaginatorInterface $paginator)
    {
        $this->episodeRepository = $episodeRepository;
        $this->urlGenerator = $urlGenerator;
        $this->paginator = $paginator;
    }

    public function getEpisodes(int $page, int $limit): array
    {
        $episodes = $this->episodeRepository->findAll();

        $data = [];

        foreach ($episodes as $episode) {
            $data[] = $this->formatEpisodeData($episode);
        }

        $count = $this->episodeRepository->getTotalEntityCount();

        $options = [
            'page' => $page,
            'entityName' => 'location',
            'limit' => $limit,
        ];

        $data = $this->paginator->paginate($data, $options);
        $info = $this->paginator->formatInfo($data, $options, $count);

        return [
            'info' => $info,
            'results' => $data,
        ];
    }

    public function getEpisode(int $episodeId): ?array
    {
        $episode = $this->episodeRepository->find($episodeId);

        if (!$episode) {
            return null;
        }

        return $this->formatEpisodeData($episode);
    }

    public function createEpisode(EpisodeDto $episodeDto): Episode
    {
        $episode = new Episode();
        $episode->setName($episodeDto->getName());
        $episode->setAirDate($episodeDto->getAirDate());
        $episode->setEpisode($episodeDto->getEpisode());
        $episode->setViews($episodeDto->getViews());
        $episode->setCreated(new DateTime());

        $this->episodeRepository->save($episode);

        return $episode;
    }

    public function updateEpisode(int $episodeId, EpisodeDto $episodeDto): ?array
    {
        $episode = $this->episodeRepository->find($episodeId);

        if (!$episode) {
            return null;
        }

        $episode->setName($episodeDto->getName());
        $episode->setAirDate($episodeDto->getAirDate());
        $episode->setEpisode($episodeDto->getEpisode());
        $episode->setViews($episodeDto->getViews());

        $this->episodeRepository->save($episode);

        return [
            'id' => $episode->getId(),
            'name' => $episode->getName(),
            'air_date' => $episode->getAirDate(),
            'episode' => $episode->getEpisode(),
            'views' => $episode->getViews(),
            'created' => $episode->getCreated(),
        ];
    }

    public function deleteEpisode(int $episodeId): bool
    {
        $episode = $this->episodeRepository->find($episodeId);

        if (!$episode) {
            return false;
        }

        $this->episodeRepository->remove($episode);

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
