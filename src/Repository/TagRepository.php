<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class TagRepository
{

    private EntityManagerInterface $entityManager;
    private EntityRepository $repository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Tag::class);
    }

    public function save(Tag $tag): void
    {
        $this->entityManager->persist($tag);
        $this->entityManager->flush();
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getByIdIn(array $tagsIds): array
    {
        return $this->repository->findBy(['id' => $tagsIds]);
    }

    public function getById(int $id): ?Tag
    {
        return $this->repository->find($id);
    }

    public function delete(int $id): void
    {
        $tag = $this->repository->find($id);
        $this->entityManager->remove($tag);
        $this->entityManager->flush();
    }
}