<?php

namespace Webfactory\VisibilityFilterBundle\Tests\Fixtures;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Webfactory\VisibilityFilterBundle\Filter\Strategy\FilterStrategy;
use Webfactory\VisibilityFilterBundle\Filter\VisibilityColumnRetriever;

class VisibilityColumnConsideringSQLFilterMock extends SQLFilter
{
    private $injectDependenciesHasBeenCalled = false;

    public function injectDependencies(FilterStrategy $filterStrategy, VisibilityColumnRetriever $visibilityColumnRetriever): void
    {
        $this->injectDependenciesHasBeenCalled = true;
    }

    public function haveDependenciesBeenInjected(): bool
    {
        return $this->injectDependenciesHasBeenCalled;
    }

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        return '';
    }
}
