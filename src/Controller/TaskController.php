<?php

namespace App\Controller;

use App\Entity\Task;
use App\Helper\TaskHelper;
use App\Repository\TaskRepository;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController
{

    private TaskRepository $taskRepository;
    private TaskHelper $taskHelper;

    public function __construct(TaskRepository $taskRepository, TaskHelper $taskHelper)
    {
        $this->taskRepository = $taskRepository;
        $this->taskHelper = $taskHelper;
    }

    /**
     * @Route("/task", methods={"POST"})
     */
    public function save(Request $request): Response
    {
        try {
            $task = $this->taskHelper->createFromJson($request->getContent());
        } catch (JsonException $e) {
            error_log($e);
            return new JsonResponse('Internal Server Error', RESPONSE::HTTP_INTERNAL_SERVER_ERROR);
        }
        $this->taskRepository->save($task);
        return new JsonResponse($task);
    }

    /**
     * @Route("/task", methods={"GET"})
     */
    public function getAll(): Response
    {
        return new JsonResponse($this->taskRepository->getAll());
    }

    /**
     * @Route("/task/{id}", methods={"GET"})
     */
    public function getOne(int $id): Response
    {
        $task = $this->taskRepository->getById($id);
        if (is_null($task)) {
            return new JsonResponse('', Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($task);
    }

    /**
     * @Route("/task/{id}", methods={"PUT"})
     */
    public function update(int $id, Request $request): Response
    {
        try {
            $task = $this->taskHelper->createFromJson($request->getContent());
        } catch (JsonException $e) {
            error_log($e);
            return new JsonResponse('Internal Server Error', RESPONSE::HTTP_INTERNAL_SERVER_ERROR);
        }
        $taskToUpdate = $this->taskRepository->getById($id);
        if (is_null($taskToUpdate)) {
            return new JsonResponse('', RESPONSE::HTTP_NOT_FOUND);
        }
        $updatedTask = new Task($task->getDescription(), $task->getTags(), $task->getProject(), $taskToUpdate->getId());
        $this->taskRepository->save($updatedTask);
        return new JsonResponse($updatedTask);
    }

    /**
     * @Route("/task/{id}", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $this->taskRepository->delete($id);
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

}