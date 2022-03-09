<?php

namespace Webfactory\VisibilityFilterBundle\DependencyInjection;

use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Webfactory\VisibilityFilterBundle\Filter\Strategy\FilterStrategy;
use Webfactory\VisibilityFilterBundle\Filter\VisibilityColumnConsideringSQLFilter;
use Webfactory\VisibilityFilterBundle\Filter\VisibilityColumnRetriever;

/**
 * Doctrine-Filters can't be served by the Symfony DI component, because Doctrine creates them without any knowledge
 * about the DI container. Therefore, this class is subscribed to the REQUEST event in order to inject the
 * dependencies at the beginning of each request.
 */
final class OnRequestDependencyInjector implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var VisibilityColumnRetriever
     */
    private $visibilityColumnRetriever;

    /**
     * @var FilterStrategy
     */
    private $filterStrategy;

    public function __construct(EntityManagerInterface $entityManager, VisibilityColumnRetriever $visibilityColumnRetriever, FilterStrategy $filterStrategy)
    {
        $this->entityManager = $entityManager;
        $this->visibilityColumnRetriever = $visibilityColumnRetriever;
        $this->filterStrategy = $filterStrategy;
    }

    public static function getSubscribedEvents()
    {
        return [KernelEvents::REQUEST => 'setUpFilter'];
    }

    public function setUpFilter(GetResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return; // filter only needs to be set up once (in the master request), as all sub request share the filter instance with the master request
        }

        $filterCollection = $this->entityManager->getFilters();

        if (!$filterCollection->has(VisibilityColumnConsideringSQLFilter::NAME)) {
            throw new RuntimeException('VisibilityFilterBundle is in use, but not set up correctly: Please register '.VisibilityColumnConsideringSQLFilter::class.' as Doctrine filter with the name "'.VisibilityColumnConsideringSQLFilter::NAME.'".');
        }

        $filterCollection->enable(VisibilityColumnConsideringSQLFilter::NAME);
        /** @var VisibilityColumnConsideringSQLFilter $visibilityFilter */
        $visibilityFilter = $filterCollection->getFilter(VisibilityColumnConsideringSQLFilter::NAME);

        $visibilityFilter->injectDependencies($this->filterStrategy, $this->visibilityColumnRetriever);
        $visibilityFilter->setParameter('visibility', $this->filterStrategy->getFilterSql(''));
    }
}
