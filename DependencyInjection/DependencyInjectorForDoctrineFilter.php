<?php

namespace Webfactory\VisibilityFilterBundle\DependencyInjection;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Webfactory\VisibilityFilterBundle\Filter\DoctrineSQLFilter;
use Webfactory\VisibilityFilterBundle\Filter\FilterStrategy;
use Webfactory\VisibilityFilterBundle\Filter\VisibilityColumnRetriever;

/**
 * Doctrine-Filter get created deep inside Doctrine and therefore can't be served by the Symfony DI component.
 * In order to inject dependencies, this class is subscribed to the REQUEST event, in order to inject the dependencies
 * at the beginning of each request.
 */
class DependencyInjectorForDoctrineFilter implements EventSubscriberInterface
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
            return;
        }

        if (!$this->entityManager->getFilters()->has(DoctrineSQLFilter::NAME)) {
            return;
        }

        $this->entityManager->getFilters()->enable(DoctrineSQLFilter::NAME);
        /** @var DoctrineSQLFilter $visibilityFilter */
        $visibilityFilter = $this->entityManager->getFilters()->getFilter(DoctrineSQLFilter::NAME);

        $visibilityFilter->setVisibilityColumnRetriever($this->visibilityColumnRetriever);
        $visibilityFilter->setFilterStrategy($this->filterStrategy);
    }
}
