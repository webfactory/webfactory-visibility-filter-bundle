<?php

namespace Webfactory\VisibilityFilterBundle\Tests\DependencyInjection;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Webfactory\VisibilityFilterBundle\Filter\Strategy\FilterStrategy;
use Webfactory\VisibilityFilterBundle\Filter\Strategy\ValueInField;
use Webfactory\VisibilityFilterBundle\Filter\VisibilityColumnConsideringSQLFilter;
use Webfactory\VisibilityFilterBundle\Tests\Fixtures\Kernel\TestKernel;
use Webfactory\VisibilityFilterBundle\Tests\Fixtures\VisibilityColumnConsideringSQLFilterMock;

class OnRequestDependencyInjectorTest extends KernelTestCase
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    protected function setUp(): void
    {
        parent::setUp();
        static::bootKernel();

        $container = static::getContainer();
        $this->eventDispatcher = $container->get(EventDispatcherInterface::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);
    }

    /**
     * @test
     */
    public function enables_filter_on_request(): void
    {
        $masterRequestEvent = new RequestEvent(static::$kernel, new Request(), HttpKernelInterface::MAIN_REQUEST);
        $this->eventDispatcher->dispatch($masterRequestEvent, KernelEvents::REQUEST);

        static::assertTrue($this->entityManager->getFilters()->isEnabled(VisibilityColumnConsideringSQLFilter::NAME));
    }

    /**
     * @test
     */
    public function injects_dependencies_into_filter(): void
    {
        $this->entityManager->getConfiguration()->addFilter(VisibilityColumnConsideringSQLFilter::NAME, VisibilityColumnConsideringSQLFilterMock::class);

        $masterRequestEvent = new RequestEvent(static::$kernel, new Request(), HttpKernelInterface::MAIN_REQUEST);
        $this->eventDispatcher->dispatch($masterRequestEvent, KernelEvents::REQUEST);

        /** @var VisibilityColumnConsideringSQLFilterMock $filterMock */
        $filterMock = $this->entityManager->getFilters()->getFilter(VisibilityColumnConsideringSQLFilter::NAME);
        static::assertTrue($filterMock->haveDependenciesBeenInjected());
    }

    /**
     * @test
     */
    public function calls_addParameters_on_strategy(): void
    {
        $strategyMock = $this->createMock(FilterStrategy::class);
        static::$container->set(ValueInField::class, $strategyMock); // alias can't be overwritten at runtime – so we override the default filter strategy

        $strategyMock->expects($this->once())->method('addParameters');

        $masterRequestEvent = new GetResponseEvent(static::$kernel, new Request(), HttpKernelInterface::MASTER_REQUEST);
        $this->eventDispatcher->dispatch($masterRequestEvent, KernelEvents::REQUEST);
    }
}
