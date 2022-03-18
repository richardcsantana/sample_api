<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ProjectRepository
{

    private EntityManagerInterface $entityManager;
    private EntityRepository $repository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Project::class);
    }

    public function save(Project $project): void
    {
        $this->entityManager->persist($project);
        $this->entityManager->flush();
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(int $projectId): ?Project
    {
        return $this->repository->find($projectId);
    }

    public function delete(int $id): void
    {
        $project = $this->repository->find($id);
        $this->entityManager->remove($project);
        $this->entityManager->flush();

    }
}