<?php

namespace Webfactory\VisibilityFilterBundle\Tests\Filter;

use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Webfactory\VisibilityFilterBundle\Tests\Fixtures\EntityWithOneToOneRelationship;
use Webfactory\VisibilityFilterBundle\Tests\Fixtures\EntityWithProperVisibilityColumn;
use Webfactory\VisibilityFilterBundle\Tests\Fixtures\EntityWithManyToManyRelationship;
use Webfactory\VisibilityFilterBundle\Tests\Fixtures\Kernel\TestKernel;

class VisibilityColumnConsideringSQLFilterTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EntityWithProperVisibilityColumn
     */
    private $invisibleEntity;

    /**
     * @var EntityWithProperVisibilityColumn
     */
    private $visibleEntity;

    protected static function getKernelClass()
    {
        return TestKernel::class;
    }

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = static::$container->get(EntityManagerInterface::class);

        // create database schema
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->createSchema([
            $this->entityManager->getClassMetadata(EntityWithProperVisibilityColumn::class),
            $this->entityManager->getClassMetadata(EntityWithManyToManyRelationship::class),
            $this->entityManager->getClassMetadata(EntityWithOneToOneRelationship::class)
        ]);

        // create test entitites
        $this->invisibleEntity = new EntityWithProperVisibilityColumn(1, 'n');
        $this->entityManager->persist($this->invisibleEntity);

        $this->visibleEntity = new EntityWithProperVisibilityColumn(2, 'y');
        $this->entityManager->persist($this->visibleEntity);

        $this->entityManager->flush();

        // activate filter by simulating a request
        $eventDispatcher = static::$container->get(EventDispatcherInterface::class);
        $masterRequestEvent = new GetResponseEvent(static::$kernel, new Request(), HttpKernelInterface::MASTER_REQUEST);
        $eventDispatcher->dispatch($masterRequestEvent, KernelEvents::REQUEST);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    private function clearUnitOfWork(): void
    {
        $this->entityManager->getUnitOfWork()->clear();
    }

    /**
     * @param int $needle
     * @param array|Collection $collection
     */
    private static function assertIsFiltered($collection): void
    {
        foreach ($collection as $entry) {
            if ($entry === null) {
                continue;
            }
            self::assertEquals('y', $entry->visibilityColumn); // using the default filter strategy, all visible entries have 'y' in their visibility column
        }
    }

    /**
     * @test
     */
    public function filter_gets_applied_on_findAll_method_of_repository(): void
    {
        $result = $this->entityManager->getRepository(EntityWithProperVisibilityColumn::class)->findAll();

        static::assertNotEmpty($result);
        static::assertIsFiltered($result);
    }

    /**
     * @test
     */
    public function filter_gets_applied_on_custom_DQL(): void
    {
        $query = $this->entityManager->createQuery('SELECT e FROM '.EntityWithProperVisibilityColumn::class.' e');
        $result = $query->getResult();

        static::assertNotEmpty($result);
        static::assertIsFiltered($result);
    }

    /**
     * @test
     */
    public function filter_gets_aplied_on_many_to_many_relationship(): void
    {
        $entityWithRelationship = new EntityWithManyToManyRelationship(1);
        $entityWithRelationship->relationship = [$this->visibleEntity, $this->invisibleEntity];
        $this->entityManager->persist($entityWithRelationship);
        $this->entityManager->flush();
        $this->clearUnitOfWork();

        /** @var EntityWithManyToManyRelationship $result */
        $result = $this->entityManager->getRepository(EntityWithManyToManyRelationship::class)->find(1);

        static::assertNotEmpty($result->relationship);
        static::assertIsFiltered($result->relationship);
    }

    /**
     * @test
     */
    public function filter_gets_aplied_on_one_to_one_relationship(): void
    {
        $entityWithRelationshipToVisible = new EntityWithOneToOneRelationship(1);
        $entityWithRelationshipToVisible->relationship = $this->visibleEntity;
        $this->entityManager->persist($entityWithRelationshipToVisible);

        $entityWithRelationshipToInvisible = new EntityWithOneToOneRelationship(2);
        $entityWithRelationshipToInvisible->relationship = $this->invisibleEntity;
        $this->entityManager->persist($entityWithRelationshipToInvisible);

        $this->entityManager->flush();
        $this->clearUnitOfWork();

        $result = $this->entityManager->getRepository(EntityWithOneToOneRelationship::class)->findAll();

        static::assertNotEmpty($result);
        static::assertCount(2, $result); // both entities should be loaded - only should one have no related entity
        static::assertIsFiltered([$result[0]->relationship, $result[1]->relationship]);
    }
}