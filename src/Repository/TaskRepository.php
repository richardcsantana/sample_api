<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class TaskRepository
{

    private EntityManagerInterface $entityManager;
    private EntityRepository $repository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Task::class);
    }

    public function save($tag): void
    {
        $this->entityManager->persist($tag);
        $this->entityManager->flush();
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(int $id): ?Task
    {
        return $this->repository->find($id);
    }

    public function delete(int $id): void
    {
        $task = $this->repository->find($id);
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }
}