<?php

namespace App\Tests\Repository;

use App\Entity\Project;
use App\Entity\Tag;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{

    private EntityManagerInterface $entityManager;
    private Tag $tag;
    private Project $project;
    private TaskRepository $repository;

    /**
     * @param Task $task
     * @return float|int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function persistAndReturnId(Task $task): mixed
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $idQueryBuilder = $this->entityManager->createQueryBuilder();
        $idQueryBuilder->select('t.id')
            ->from(Task::class, 't')
            ->setMaxResults(1);

        return $idQueryBuilder->getQuery()->getSingleScalarResult();
    }

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $query = $this->entityManager->createQuery('delete from App\Entity\Task t');
        $query->execute();

        $query = $this->entityManager->createQuery('delete from App\Entity\Tag t');
        $query->execute();

        $query = $this->entityManager->createQuery('delete from App\Entity\Project p');
        $query->execute();
        $this->entityManager->flush();

        $this->tag = new Tag('description', 1);
        $this->entityManager->persist($this->tag);

        $this->project = new Project('description', 1);
        $this->entityManager->persist($this->project);

        $this->entityManager->flush();

        $this->repository = new TaskRepository($this->entityManager);
    }


    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function testDelete(): void
    {
        $id = $this->persistAndReturnId(new Task('test',
            new ArrayCollection(array($this->tag)), $this->project));

        $this->repository->delete($id);

        $counterQueryBuilder = $this->entityManager->createQueryBuilder();
        $counterQueryBuilder->select('count(t.id)')
            ->from(Task::class, 't');
        $result = $counterQueryBuilder->getQuery()->getSingleScalarResult();

        $this->assertEquals(0, $result);
    }

    public function testGetById()
    {

        $id = $this->persistAndReturnId(new Task('test',
            new ArrayCollection(array($this->tag)), $this->project));

        $task = $this->repository->getById($id);
        $this->assertNotNull($task);
        $this->assertEquals('test', $task->getDescription());
        $this->assertNotEmpty($task->getTags());
        $this->assertCount(1, $task->getTags());
        $this->assertNotNull($task->getProject());
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function testGetAll(): void
    {

        $id = $this->persistAndReturnId(new Task('test',
            new ArrayCollection(array($this->tag)), $this->project));

        $tasks = $this->repository->getAll();
        $this->assertNotEmpty($tasks);
        $task = $tasks[0];
        $this->assertNotNull($task);
        $this->assertEquals('test', $task->getDescription());
        $this->assertNotEmpty($task->getTags());
        $this->assertCount(1, $task->getTags());
        $this->assertNotNull($task->getProject());
    }

    public function testSave(): void
    {
        $this->repository->save(new Task('test',
            new ArrayCollection(array($this->tag)), $this->project));

        $counterQueryBuilder = $this->entityManager->createQueryBuilder();
        $counterQueryBuilder->select('t')
            ->from(Task::class, 't');
        $tasks = $counterQueryBuilder->getQuery()->getResult();
        $this->assertNotEmpty($tasks);
        $task = $tasks[0];
        $this->assertNotNull($task);
        $this->assertEquals('test', $task->getDescription());
        $this->assertNotEmpty($task->getTags());
        $this->assertCount(1, $task->getTags());
        $this->assertNotNull($task->getProject());
    }
}
