<?php

namespace App\Tests\Repository;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProjectRepositoryTest extends KernelTestCase
{

    private EntityManagerInterface $entityManager;
    private ProjectRepository $repository;

    /**
     * @param Project $project
     * @return float|int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function persistAndReturnId(Project $project): mixed
    {
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        $idQueryBuilder = $this->entityManager->createQueryBuilder();
        $idQueryBuilder->select('t.id')
            ->from(Project::class, 't')
            ->setMaxResults(1);

        return $idQueryBuilder->getQuery()->getSingleScalarResult();
    }

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $query = $this->entityManager->createQuery('delete from App\Entity\Project t');
        $query->execute();

        $this->entityManager->flush();

        $this->repository = new ProjectRepository($this->entityManager);
    }


    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function testDelete(): void
    {
        $id = $this->persistAndReturnId(new Project('test'));

        $this->repository->delete($id);

        $counterQueryBuilder = $this->entityManager->createQueryBuilder();
        $counterQueryBuilder->select('count(t.id)')
            ->from(Project::class, 't');
        $result = $counterQueryBuilder->getQuery()->getSingleScalarResult();

        $this->assertEquals(0, $result);
    }

    public function testGetById()
    {

        $id = $this->persistAndReturnId(new Project('test'));

        $project = $this->repository->getById($id);
        $this->assertNotNull($project);
        $this->assertEquals('test', $project->getDescription());
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function testGetAll(): void
    {

        $id = $this->persistAndReturnId(new Project('test'));

        $projects = $this->repository->getAll();
        $this->assertNotEmpty($projects);
        $project = $projects[0];
        $this->assertNotNull($project);
        $this->assertEquals('test', $project->getDescription());
    }

    public function testSave(): void
    {
        $this->repository->save(new Project('test'));

        $counterQueryBuilder = $this->entityManager->createQueryBuilder();
        $counterQueryBuilder->select('t')
            ->from(Project::class, 't');
        $projects = $counterQueryBuilder->getQuery()->getResult();
        $this->assertNotEmpty($projects);
        $project = $projects[0];
        $this->assertNotNull($project);
        $this->assertEquals('test', $project->getDescription());
    }
}
