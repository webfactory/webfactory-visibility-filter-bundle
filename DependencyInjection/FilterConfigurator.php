<?php

namespace Webfactory\VisibilityFilterBundle\DependencyInjection;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Webfactory\VisibilityFilterBundle\Filter\DoctrineSQLFilter;
use Webfactory\VisibilityFilterBundle\Filter\FilterStrategy;
use Webfactory\VisibilityFilterBundle\Filter\VisibilityColumnRetriever;

class FilterConfigurator implements EventSubscriberInterface
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

        /** @var DoctrineSQLFilter $visibilityFilter */
        $visibilityFilter = $this->entityManager->getFilters()->getFilter(DoctrineSQLFilter::NAME);
        $visibilityFilter->setVisibilityColumnRetriever($this->visibilityColumnRetriever);
        $visibilityFilter->setFilterRule($this->filterRule);
    }
}
