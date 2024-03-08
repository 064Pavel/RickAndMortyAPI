<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CharacterDto;
use App\Service\CharacterService;
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

    public function __construct(CharacterService $characterService,
        SerializerInterface $serializer,
        ValidatorInterface $validator)
    {
        $this->characterService = $characterService;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('/api/character', name: 'all.character', methods: 'GET')]
    public function getAllCharacter(Request $request): JsonResponse
    {
        $data = $this->characterService->getCharacters();

        if (!$data) {
            return $this->json([]);
        }

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/api/character/{id}', name: 'get.character', methods: 'GET')]
    public function getCharacter(int $id): JsonResponse
    {
        $data = $this->characterService->getCharacter($id);

        if (!$data) {
            return $this->json([]);
        }

        return $this->json(['result' => $data], Response::HTTP_OK);
    }

    #[Route('/api/character', name: 'create.character', methods: 'POST')]
    public function createCharacter(Request $request): JsonResponse
    {
        $characterDto = $this->serializer->deserialize($request->getContent(), CharacterDto::class, 'json');

        $errors = $this->validator->validate($characterDto);
        if (count($errors) > 0) {
            $errorsMessage = (string) $errors;

            return $this->json(['errors' => $errorsMessage], Response::HTTP_BAD_REQUEST);
        }

        $context = [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['origin', 'location', 'episodes'],
        ];

        $data = $this->characterService->createCharacter($characterDto, $context);

        return $this->json(['result' => $data], Response::HTTP_CREATED);
    }

    #[Route('/api/character/{id}', name: 'update.character', methods: ['PUT', 'PATCH'])]
    public function updateCharacter(int $id, Request $request): JsonResponse
    {
        $characterDto = $this->serializer->deserialize($request->getContent(), CharacterDto::class, 'json');

        $errors = $this->validator->validate($characterDto);
        if (count($errors) > 0) {
            $errorsMessage = (string) $errors;

            return $this->json(['errors' => $errorsMessage], Response::HTTP_BAD_REQUEST);
        }

        $context = [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['origin', 'location', 'episodes'],
        ];

        $data = $this->characterService->updateCharacter($id, $characterDto, $context);

        return $this->json(['result' => $data], Response::HTTP_CREATED);
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
}
