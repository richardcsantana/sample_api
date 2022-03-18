<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Helper\TagHelper;
use App\Repository\TagRepository;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController
{

    private TagRepository $tagRepository;
    private TagHelper $tagHelper;

    public function __construct(TagRepository $tagRepository, TagHelper $tagHelper)
    {
        $this->tagRepository = $tagRepository;
        $this->tagHelper = $tagHelper;
    }

    /**
     * @Route("/tag", methods={"POST"})
     */
    public function save(Request $request): JsonResponse
    {
        try {
            $tag = $this->tagHelper->createFromJson($request->getContent());
        } catch (JsonException $e) {
            error_log($e);
            return new JsonResponse('Internal Server Error', RESPONSE::HTTP_INTERNAL_SERVER_ERROR);
        }
        $this->tagRepository->save($tag);
        return new JsonResponse($tag);
    }

    /**
     * @Route("/tag", methods={"GET"})
     */
    public function getAll(): Response
    {
        $all = $this->tagRepository->getAll();
        return new JsonResponse($all);
    }

    /**
     * @Route("/tag/{id}", methods={"GET"})
     */
    public function getOne(int $id): Response
    {
        $tag = $this->tagRepository->getById($id);
        if (is_null($tag)) {
            return new JsonResponse('', Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($tag);
    }

    /**
     * @Route("/tag/{id}", methods={"PUT"})
     */
    public function update(int $id, Request $request): Response
    {
        try {
            $tag = $this->tagHelper->createFromJson($request->getContent());
        } catch (JsonException $e) {
            error_log($e);
            return new JsonResponse('Internal Server Error', RESPONSE::HTTP_INTERNAL_SERVER_ERROR);
        }
        $tagToUpdate = $this->tagRepository->getById($id);
        if (is_null($tagToUpdate)) {
            return new JsonResponse('', RESPONSE::HTTP_NOT_FOUND);
        }
        $updatedTag = new Tag($tag->getDescription(), $tagToUpdate->getId());
        $this->tagRepository->save($updatedTag);
        return new JsonResponse($updatedTag);
    }

    /**
     * @Route("/tag/{id}", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $this->tagRepository->delete($id);
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}