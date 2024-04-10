<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CharacterDto;
use App\Service\CharacterService;
use App\Service\PaginationService;
use App\Tools\QueryFilterInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class CharacterController extends AbstractController
{
    public function __construct(private CharacterService $characterService,
        private QueryFilterInterface $queryFilter,
        private PaginationService $paginationService,
        private SerializerInterface $serializer, )
    {
    }

    #[Route('/api/character', name: 'all.character', methods: 'GET')]
    public function getAllCharacter(Request $request): JsonResponse
    {
        try {
            [$page, $limit] = $this->paginationService->getPageAndLimit($request);

            $allowedFilters = ['name', 'status', 'species', 'type', 'gender'];

            $queries = $this->queryFilter->filter($request, $allowedFilters);

            $data = $this->characterService->getEntities($page, $limit, $queries);

            if (empty($data)) {
                return $this->json(['message' => 'nothing could be found on the request']);
            }

            return $this->json($data);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while fetching characters'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/characters/{ids}', name: 'character.by.ids', methods: 'GET')]
    public function getAllCharacterByIds(string $ids, Request $request): JsonResponse
    {
        try {
            [$page, $limit] = $this->paginationService->getPageAndLimit($request);

            $data = $this->characterService->getEntitiesByIds($page, $limit, $ids);

            if (empty($data)) {
                return $this->json(['message' => 'nothing could be found on the request']);
            }

            return $this->json($data);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while fetching character by ids'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/character/{id}', name: 'get.character', requirements: ['id' => '\d+'], methods: 'GET')]
    public function getCharacter(int $id): JsonResponse
    {
        try {
            $data = $this->characterService->getEntity($id);

            if (empty($data)) {
                return $this->json(['message' => 'nothing could be found on the request']);
            }

            return $this->json(['result' => $data]);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while fetching character'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/character', name: 'create.character', methods: 'POST')]
    public function createCharacter(CharacterDto $characterDto): JsonResponse
    {
        $data = $this->characterService->createEntity($characterDto);

        return $this->serializeCharacter($data);
    }

    #[Route('/api/character/{id}', name: 'put.update.character', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function putUpdateCharacter(int $id, CharacterDto $characterDto): JsonResponse
    {
        try {
            $data = $this->characterService->putUpdateEntity($id, $characterDto);

            if (empty($data)) {
                return $this->json(['message' => 'there is no such entity']);
            }

            return $this->serializeCharacter($data);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while updating the episode'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/character/{id}', name: 'patch.update.character', requirements: ['id' => '\d+'], methods: ['PATCH'])]
    public function patchUpdateCharacter(int $id, CharacterDto $characterDto): JsonResponse
    {
        try {
            $data = $this->characterService->patchUpdateEntity($id, $characterDto);

            if (empty($data)) {
                return $this->json(['message' => 'there is no such entity']);
            }

            return $this->serializeCharacter($data);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while updating the episode'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/character/{id}', name: 'delete.character', requirements: ['id' => '\d+'], methods: 'DELETE')]
    public function deleteCharacter(int $id): JsonResponse
    {
        try {
            $isDelete = $this->characterService->deleteEntity($id);

            if (!$isDelete) {
                return $this->json(['message' => 'failure'], Response::HTTP_BAD_REQUEST);
            }

            return $this->json(['message' => 'Character successfully deleted'], Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return $this->json(['message' => 'An error occurred while deleting the episode'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function serializeCharacter(array $data): JsonResponse
    {
        $context = [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['origin', 'location', 'episodes'],
        ];

        $serializedCharacter = $this->serializer->serialize($data, 'json', $context);

        $data = json_decode($serializedCharacter, true);

        return $this->json(['result' => $data], Response::HTTP_CREATED, $context);
    }
}
