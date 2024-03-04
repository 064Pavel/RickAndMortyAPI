<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\EpisodeDto;
use App\Entity\Episode;
use App\Repository\EpisodeRepository;
use DateTime;

class EpisodeService
{
    private EpisodeRepository $episodeRepository;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(EpisodeRepository $episodeRepository,
        UrlGeneratorInterface $urlGenerator)
    {
        $this->episodeRepository = $episodeRepository;
        $this->urlGenerator = $urlGenerator;
    }

    public function getEpisodes(): array
    {
        $episodes = $this->episodeRepository->findAll();

        $data = [];

        foreach ($episodes as $episode) {
            $data[] = $this->formatEpisodeData($episode);
        }

        return $data;
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
