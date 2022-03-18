<?php

namespace App\Tests\Repository;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TagRepositoryTest extends KernelTestCase
{

    private EntityManagerInterface $entityManager;
    private TagRepository $repository;

    /**
     * @param Tag $tag
     * @return float|int|mixed|string
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function persistAndReturnId(Tag $tag): mixed
    {
        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        $idQueryBuilder = $this->entityManager->createQueryBuilder();
        $idQueryBuilder->select('t.id')
            ->from(Tag::class, 't')
            ->setMaxResults(1);

        return $idQueryBuilder->getQuery()->getSingleScalarResult();
    }

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $query = $this->entityManager->createQuery('delete from App\Entity\Tag t');
        $query->execute();

        $this->entityManager->flush();

        $this->repository = new TagRepository($this->entityManager);
    }


    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function testDelete(): void
    {
        $id = $this->persistAndReturnId(new Tag('test'));

        $this->repository->delete($id);

        $counterQueryBuilder = $this->entityManager->createQueryBuilder();
        $counterQueryBuilder->select('count(t.id)')
            ->from(Tag::class, 't');
        $result = $counterQueryBuilder->getQuery()->getSingleScalarResult();

        $this->assertEquals(0, $result);
    }

    public function testGetById()
    {

        $id = $this->persistAndReturnId(new Tag('test'));

        $tag = $this->repository->getById($id);
        $this->assertNotNull($tag);
        $this->assertEquals('test', $tag->getDescription());
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function testGetAll(): void
    {

        $id = $this->persistAndReturnId(new Tag('test'));

        $tags = $this->repository->getAll();
        $this->assertNotEmpty($tags);
        $tag = $tags[0];
        $this->assertNotNull($tag);
        $this->assertEquals('test', $tag->getDescription());
    }

    public function testSave(): void
    {
        $this->repository->save(new Tag('test'));

        $counterQueryBuilder = $this->entityManager->createQueryBuilder();
        $counterQueryBuilder->select('t')
            ->from(Tag::class, 't');
        $tags = $counterQueryBuilder->getQuery()->getResult();
        $this->assertNotEmpty($tags);
        $tag = $tags[0];
        $this->assertNotNull($tag);
        $this->assertEquals('test', $tag->getDescription());
    }
}
