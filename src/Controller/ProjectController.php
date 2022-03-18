<?php

namespace App\Controller;

use App\Entity\Project;
use App\Helper\ProjectHelper;
use App\Repository\ProjectRepository;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController
{

    private ProjectRepository $projectRepository;
    private ProjectHelper $projectHelper;

    public function __construct(ProjectRepository $projectRepository, ProjectHelper $projectHelper)
    {
        $this->projectRepository = $projectRepository;
        $this->projectHelper = $projectHelper;
    }

    /**
     * @Route("/project", methods={"POST"})
     */
    public function save(Request $request): JsonResponse
    {
        try {
            $project = $this->projectHelper->createFromJson($request->getContent());
        } catch (JsonException $e) {
            error_log($e);
            return new JsonResponse('Internal Server Error', RESPONSE::HTTP_INTERNAL_SERVER_ERROR);
        }
        $this->projectRepository->save($project);
        return new JsonResponse($project);
    }

    /**
     * @Route("/project", methods={"GET"})
     */
    public function getAll(): Response
    {
        return new JsonResponse($this->projectRepository->getAll());
    }

    /**
     * @Route("/project/{id}", methods={"GET"})
     */
    public function getOne(int $id): Response
    {
        $project = $this->projectRepository->getById($id);
        if (is_null($project)) {
            return new JsonResponse('', Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($project);
    }

    /**
     * @Route("/project/{id}", methods={"PUT"})
     */
    public function update(int $id, Request $request): Response
    {
        try {
            $project = $this->projectHelper->createFromJson($request->getContent());
        } catch (JsonException $e) {
            error_log($e);
            return new JsonResponse('Internal Server Error', RESPONSE::HTTP_INTERNAL_SERVER_ERROR);
        }
        $projectToUpdate = $this->projectRepository->getById($id);
        if (is_null($projectToUpdate)) {
            return new JsonResponse('', RESPONSE::HTTP_NOT_FOUND);
        }
        $updatedProject = new Project($project->getDescription(), $projectToUpdate->getId());
        $this->projectRepository->save($updatedProject);
        return new JsonResponse($updatedProject);
    }

    /**
     * @Route("/project/{id}", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $this->projectRepository->delete($id);
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}