<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\EpisodeDto;
use App\Service\EpisodeService;
use App\Tools\DataConverterInterface;
use App\Tools\QueryFilterInterface;
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

    private QueryFilterInterface $queryFilter;

    public function __construct(EpisodeService $episodeService,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        QueryFilterInterface $queryFilter, private DataConverterInterface $dataConverter)
    {
        $this->episodeService = $episodeService;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->queryFilter = $queryFilter;
    }

    #[Route('/api/episode', name: 'all.episode', methods: 'GET')]
    public function getAllEpisode(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $allowedFilters = ['name', 'episode'];

        $queries = $this->queryFilter->filter($request, $allowedFilters);

        $data = $this->episodeService->getEpisodes($page, $limit, $queries);

        if (empty($data)) {
            return $this->json(['message' => 'no data available']);
        }

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/api/episodes/{ids}', name: 'all.episode.by.ids', methods: 'GET')]
    public function getAllEpisodeByIds(string $ids): JsonResponse
    {
        $data = $this->episodeService->getEpisodesByIds($ids);

        if (empty($data)) {
            return $this->json(['message' => 'nothing could be found on the request']);
        }

        return $this->json($data);
    }

    #[Route('/api/episode/{id}', name: 'get.episode', methods: 'GET')]
    public function getEpisode(int $id): JsonResponse
    {
        $data = $this->episodeService->getEpisode($id);

        if (empty($data)) {
            return $this->json(['message' => 'nothing could be found on the request']);
        }

        return $this->json(['result' => $data], Response::HTTP_OK);
    }

    #[Route('/api/episode', name: 'create.episode', methods: 'POST')]
    public function createEpisode(Request $request): JsonResponse
    {
        $episodeDto = $this->processEpisodeDtoRequest($request);

        $errors = $this->validator->validate($episodeDto);
        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json(['errors' => $errorsArray], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->episodeService->createEpisode($episodeDto);

        return $this->json(['result' => $data], Response::HTTP_CREATED);
    }

    #[Route('/api/episode/{id}', name: 'update.episode', methods: ['PUT'])]
    public function updateEpisode(int $id, Request $request): JsonResponse
    {
        $episodeDto = $this->processEpisodeDtoRequest($request);

        $errors = $this->validator->validate($episodeDto);
        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json(['errors' => $errorsArray], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->episodeService->updateEpisode($id, $episodeDto);

        return $this->json(['result' => $data], Response::HTTP_OK);
    }

    #[Route('/api/episode/{id}', name: 'patch.episode', methods: ['PATCH'])]
    public function patchEpisode(int $id, Request $request): JsonResponse
    {
        $episodeDto = $this->processEpisodeDtoRequest($request);

        $errors = $this->validator->validate($episodeDto, null, ['patch']);
        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json(['errors' => $errorsArray], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->episodeService->patchLocation($id, $episodeDto);

        if (empty($data)) {
            return $this->json(['message' => 'there is no such entity']);
        }

        return $this->json(['result' => $data]);
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

    private function processEpisodeDtoRequest(Request $request): EpisodeDto
    {
        $convertFields = ['name', 'air_date', 'episode'];

        $jsonData = $this->dataConvert($request, $convertFields);

        return $this->serializer->deserialize($jsonData, EpisodeDto::class, 'json');
    }

    private function dataConvert(Request $request, array $fieldsToConvert): string
    {
        $requestData = json_decode($request->getContent(), true);

        if (null === $requestData) {
            return '';
        }

        $this->dataConverter->convertToString($requestData, $fieldsToConvert);

        return json_encode($requestData);
    }
}
