<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\EpisodeDto;
use App\Service\EpisodeService;
use App\Service\PaginationService;
use App\Tools\QueryFilterInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EpisodeController extends AbstractController
{
    public function __construct(private EpisodeService $episodeService,
        private QueryFilterInterface $queryFilter,
        private PaginationService $paginationService, )
    {
    }

    #[Route('/api/episode', name: 'all.episode', methods: ['GET'])]
    public function getAllEpisode(Request $request): JsonResponse
    {
        try {
            [$page, $limit] = $this->paginationService->getPageAndLimit($request);

            $allowedFilters = ['name', 'episode'];

            $queries = $this->queryFilter->filter($request, $allowedFilters);

            $data = $this->episodeService->getEntities($page, $limit, $queries);

            if (empty($data)) {
                return $this->json(['message' => 'no data available']);
            }

            return $this->json($data);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while fetching episodes'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/episodes/{ids}', name: 'episode.by.ids', methods: 'GET')]
    public function getAllEpisodeByIds(string $ids, Request $request): JsonResponse
    {
        try {
            [$page, $limit] = $this->paginationService->getPageAndLimit($request);

            $data = $this->episodeService->getEntitiesByIds($page, $limit, $ids);

            if (empty($data)) {
                return $this->json(['message' => 'nothing could be found on the request']);
            }

            return $this->json($data);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while fetching episodes by ids'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/episode/{id}', name: 'get.episode', requirements: ['id' => '\d+'], methods: 'GET')]
    public function getEpisode(int $id): JsonResponse
    {
        try {
            $data = $this->episodeService->getEntity($id);

            if (empty($data)) {
                return $this->json(['message' => 'nothing could be found on the request']);
            }

            return $this->json(['result' => $data]);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while fetching episode'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/episode', name: 'create.episode', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function createEpisode(EpisodeDto $episodeDto): JsonResponse
    {
        try {
            $data = $this->episodeService->createEntity($episodeDto);

            return $this->json(['result' => $data], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while creating the episode'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/episode/{id}', name: 'put.update.episode', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function putUpdateEpisode(int $id, EpisodeDto $episodeDto): JsonResponse
    {
        try {
            $data = $this->episodeService->putUpdateEntity($id, $episodeDto);

            if (empty($data)) {
                return $this->json(['message' => 'there is no such entity']);
            }

            return $this->json(['result' => $data]);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while updating the episode'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/episode/{id}', name: 'patch.update.episode', requirements: ['id' => '\d+'], methods: ['PATCH'])]
    public function patchUpdateEpisode(int $id, EpisodeDto $episodeDto): JsonResponse
    {
        try {
            $data = $this->episodeService->patchUpdateEntity($id, $episodeDto);

            if (empty($data)) {
                return $this->json(['message' => 'there is no such entity']);
            }

            return $this->json(['result' => $data]);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while updating the episode'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/episode/{id}', name: 'delete.episode', requirements: ['id' => '\d+'], methods: 'DELETE')]
    public function deleteEpisode(int $id): JsonResponse
    {
        try {
            $isDelete = $this->episodeService->deleteEntity($id);

            if (!$isDelete) {
                return $this->json(['message' => 'failure', Response::HTTP_BAD_REQUEST]);
            }

            return $this->json(['message' => 'Episode successfully deleted'], Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while deleting the episode'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
