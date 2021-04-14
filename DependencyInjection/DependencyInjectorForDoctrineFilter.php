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
 * Doctrine-Filter werden tief in Doctrine erzeugt und können daher nicht von der Symfony-DI-Komponente bedient werden.
 * Deshalb subscribet diese Klasse dem REQUEST-Event, um am Anfang jedes Requests die nötigen Dependencies in den Filter zu injizieren.
 */
class DependencyInjectorForDoctrineFilter implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var VisibilityColumnRetriever     */
    private $visibilityColumnRetriever;

    /**
     * @var FilterStrategy
     */
    private $filterRule;

    public function __construct(EntityManagerInterface $entityManager, VisibilityColumnRetriever $visibilityColumnRetriever, FilterStrategy $filterRule)
    {
        $this->entityManager = $entityManager;
        $this->visibilityColumnRetriever = $visibilityColumnRetriever;
        $this->filterRule = $filterRule;
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
        $visibilityFilter->setFilterRule($this->filterRule);
    }
}
