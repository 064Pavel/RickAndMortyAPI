<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CharacterDto;
use App\Service\CharacterService;
use App\Tools\DataConverterInterface;
use App\Tools\QueryFilterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CharacterController extends AbstractController
{
    private CharacterService $characterService;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;
    private QueryFilterInterface $queryFilter;

    public function __construct(CharacterService $characterService,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        QueryFilterInterface $queryFilter, private DataConverterInterface $dataConverter)
    {
        $this->characterService = $characterService;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->queryFilter = $queryFilter;
    }

    #[Route('/api/character', name: 'all.character', methods: 'GET')]
    public function getAllCharacter(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $allowedFilters = ['name', 'status', 'species', 'type', 'gender'];

        $queries = $this->queryFilter->filter($request, $allowedFilters);

        $data = $this->characterService->getCharacters($page, $limit, $queries);

        if (empty($data)) {
            return $this->json(['message' => 'nothing could be found on the request']);
        }

        return $this->json($data);
    }

    #[Route('/api/characters/{ids}', name: 'all.character.by.ids', methods: 'GET')]
    public function getAllCharacterByIds(string $ids): JsonResponse
    {
        $data = $this->characterService->getCharactersByIds($ids);

        if (empty($data)) {
            return $this->json(['message' => 'nothing could be found on the request']);
        }

        return $this->json($data);
    }

    #[Route('/api/character/{id}', name: 'get.character', methods: 'GET')]
    public function getCharacter(int $id): JsonResponse
    {
        $data = $this->characterService->getCharacter($id);

        if (empty($data)) {
            return $this->json(['message' => 'nothing could be found on the request']);
        }

        return $this->json(['result' => $data], Response::HTTP_OK);
    }

    #[Route('/api/character', name: 'create.character', methods: 'POST')]
    public function createCharacter(Request $request): JsonResponse
    {
        $characterDto = $this->processCharacterDtoRequest($request);

        $errors = $this->validator->validate($characterDto);
        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json(['errors' => $errorsArray], Response::HTTP_BAD_REQUEST);
        }

        $context = [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['origin', 'location', 'episodes'],
        ];

        $data = $this->characterService->createCharacter($characterDto, $context);

        return $this->json(['result' => $data], Response::HTTP_CREATED);
    }

    #[Route('/api/character/{id}', name: 'update.character', methods: ['PUT'])]
    public function updateCharacter(int $id, Request $request): JsonResponse
    {
        $characterDto = $this->processCharacterDtoRequest($request);

        $errors = $this->validator->validate($characterDto);
        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json(['errors' => $errorsArray], Response::HTTP_BAD_REQUEST);
        }

        $context = [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['origin', 'location', 'episodes'],
        ];

        $data = $this->characterService->updateCharacter($id, $characterDto, $context);

        return $this->json(['result' => $data]);
    }

    #[Route('/api/character/{id}', name: 'patch.character', methods: ['PATCH'])]
    public function patchCharacter(int $id, Request $request): JsonResponse
    {
        $characterDto = $this->processCharacterDtoRequest($request);

        $errors = $this->validator->validate($characterDto, null, ['patch']);
        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json(['errors' => $errorsArray], Response::HTTP_BAD_REQUEST);
        }

        $data = $this->characterService->patchCharacter($id, $characterDto);

        if (empty($data)) {
            return $this->json(['message' => 'there is no such entity']);
        }

        return $this->json(['result' => $data]);
    }

    #[Route('/api/character/{id}', name: 'delete.character', methods: 'DELETE')]
    public function deleteCharacter(int $id): JsonResponse
    {
        $isDelete = $this->characterService->deleteCharacter($id);

        if (!$isDelete) {
            return $this->json(['message' => 'failure', Response::HTTP_BAD_REQUEST]);
        }

        return $this->json(['message' => 'success']);
    }

    private function processCharacterDtoRequest(Request $request): CharacterDto
    {
        $convertFields = ['name', 'status', 'species', 'type', 'gender', 'image'];

        $jsonData = $this->dataConvert($request, $convertFields);

        return $this->serializer->deserialize($jsonData, CharacterDto::class, 'json');
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
