<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\EpisodeDto;
use App\Service\EpisodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EpisodeController extends AbstractController
{
    private EpisodeService $episodeService;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(EpisodeService $episodeService,
        SerializerInterface $serializer,
        ValidatorInterface $validator)
    {
        $this->episodeService = $episodeService;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('/api/episode', name: 'all.episode', methods: 'GET')]
    public function getAllEpisode(Request $request): JsonResponse
    {
        $data = $this->episodeService->getEpisodes();

        if (!$data) {
            return $this->json([]);
        }

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/api/episode/{id}', name: 'get.episode', methods: 'GET')]
    public function getEpisode(int $id): JsonResponse
    {
        $data = $this->episodeService->getEpisode($id);

        if (!$data) {
            return $this->json([]);
        }

        return $this->json(['result' => $data], Response::HTTP_OK);
    }

    #[Route('/api/episode', name: 'create.episode', methods: 'POST')]
    public function createEpisode(Request $request): JsonResponse
    {
        $episodeDto = $this->serializer->deserialize($request->getContent(), EpisodeDto::class, 'json');

        $errors = $this->validator->validate($episodeDto);
        if (count($errors) > 0) {
            $errorsMessage = (string) $errors;

            return $this->json(['errors' => $errorsMessage], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->episodeService->createEpisode($episodeDto);

        return $this->json(['result' => $data], Response::HTTP_CREATED);
    }

    #[Route('/api/episode/{id}', name: 'update.episode', methods: ['PUT', 'PATCH'])]
    public function updateEpisode(int $id, Request $request): JsonResponse
    {
        $EpisodeDto = $this->serializer->deserialize($request->getContent(), EpisodeDto::class, 'json');

        $errors = $this->validator->validate($EpisodeDto);
        if (count($errors) > 0) {
            $errorsMessage = (string) $errors;

            return $this->json(['errors' => $errorsMessage], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->episodeService->updateEpisode($id, $EpisodeDto);

        return $this->json(['result' => $data], Response::HTTP_OK);
    }

    #[Route('/api/episode/{id}', name: 'delete.episode', methods: 'DELETE')]
    public function deleteEpisode(int $id): JsonResponse
    {
        $isDelete = $this->episodeService->deleteEpisode($id);

        if (!$isDelete) {
            return $this->json(['message' => 'failure', Response::HTTP_BAD_REQUEST]);
        }

        return $this->json(['message' => 'success']);
    }
}
