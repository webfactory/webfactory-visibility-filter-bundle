<?php

namespace Webfactory\VisibilityFilterBundle\Tests\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Mapping\ClassMetadataFactory;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Webfactory\VisibilityFilterBundle\Filter\VisibilityColumnRetriever;
use Webfactory\VisibilityFilterBundle\Tests\Fixtures\EntityWithFaultyVisibilityColumn;
use Webfactory\VisibilityFilterBundle\Tests\Fixtures\EntityWithNoVisibilityColumn;
use Webfactory\VisibilityFilterBundle\Tests\Fixtures\EntityWithProperVisibilityColumn;
use Webfactory\VisibilityFilterBundle\Tests\Fixtures\Kernel\TestKernel;

class VisibilityColumnRetrieverTest extends KernelTestCase
{
    /**
     * @var VisibilityColumnRetriever
     */
    private $visibilityColumnRetriever;

    /**
     * @var ClassMetadataFactory
     */
    private $classMetadataFactory;

    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    protected function setUp(): void
    {
        parent::setUp();
        static::bootKernel();

        $container = static::getContainer();
        $this->visibilityColumnRetriever = $container->get(VisibilityColumnRetriever::class);
        $this->classMetadataFactory = $container->get(EntityManagerInterface::class)->getMetadataFactory();
    }

    /**
     * @test
     */
    public function finds_properly_configured_visibility_column(): void
    {
        $classMetadata = $this->classMetadataFactory->getMetadataFor(EntityWithProperVisibilityColumn::class);

        $result = $this->visibilityColumnRetriever->getVisibilityColumnName($classMetadata);

        static::assertEquals('visibility_column', $result);
    }

    /**
     * @test
     */
    public function errors_on_faulty_configured_visibility_column(): void
    {
        $this->expectException(RuntimeException::class);

        $classMetadata = $this->classMetadataFactory->getMetadataFor(EntityWithFaultyVisibilityColumn::class);

        $this->visibilityColumnRetriever->getVisibilityColumnName($classMetadata);
    }

    /**
     * @test
     */
    public function returns_null_when_there_is_no_visibility_column(): void
    {
        $classMetadata = $this->classMetadataFactory->getMetadataFor(EntityWithNoVisibilityColumn::class);

        $result = $this->visibilityColumnRetriever->getVisibilityColumnName($classMetadata);

        static::assertNull($result);
    }
}
