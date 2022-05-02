<?php

namespace Webfactory\VisibilityFilterBundle\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use RuntimeException;
use Webfactory\VisibilityFilterBundle\Filter\Strategy\FilterStrategy;

final class VisibilityColumnConsideringSQLFilter extends SQLFilter
{
    public const NAME = 'visibility';

    /**
     * @var FilterStrategy
     */
    private $filterStrategy;

    /**
     * @var VisibilityColumnRetriever
     */
    private $visiblityColumnRetriever;

    public function injectDependencies(FilterStrategy $filterStrategy, VisibilityColumnRetriever $visibilityColumnRetriever): void
    {
        $this->filterStrategy = $filterStrategy;
        $this->visiblityColumnRetriever = $visibilityColumnRetriever;
    }

    /**
     * @return string
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        $this->assertSetUpCorrectly();

        $visibilityColumn = $this->visiblityColumnRetriever->getVisibilityColumnName($targetEntity);

        if (null === $visibilityColumn) {
            return ''; // Entity doesn't have visibility information
        }

        return $this->filterStrategy->getFilterSql($targetTableAlias.'.'.$visibilityColumn, new SQLFilterAsParameterCollection($this));
    }

    private function assertSetUpCorrectly(): void
    {
        if (null !== $this->filterStrategy && null !== $this->visiblityColumnRetriever) {
            return;
        }

        throw new RuntimeException('Filter not set up correctly: You need to inject a '.FilterStrategy::class.' and a '.VisibilityColumnRetriever::class.' before using the filter.');
    }
}
