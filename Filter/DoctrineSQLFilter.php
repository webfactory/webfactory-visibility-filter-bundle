<?php

namespace Webfactory\VisibilityFilterBundle\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use RuntimeException;

class DoctrineSQLFilter extends SQLFilter
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

    public function setFilterStrategy(FilterStrategy $filterStrategy): void
    {
        $this->filterStrategy = $filterStrategy;
    }

    public function setVisibilityColumnRetriever(VisibilityColumnRetriever $visibilityColumnRetriever)
    {
        $this->visiblityColumnRetriever = $visibilityColumnRetriever;
    }

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        $this->assertSetUpCorrectly();

        $visibilityColumn = $this->visiblityColumnRetriever->getVisibilityColumnName($targetEntity);

        if ($visibilityColumn === null) {
            return ''; // Entity doesn't have visibility information
        }

        return $this->filterStrategy->getFilterSql($targetTableAlias.'.'.$visibilityColumn);
    }

    private function assertSetUpCorrectly(): void
    {
        if ($this->filterStrategy !== null && $this->visiblityColumnRetriever !== null) {
            return;
        }

        throw new RuntimeException('Filter not set up correctly: You need to inject a '.FilterStrategy::class.' and a '.VisibilityColumnRetriever::class.' via setter before using the filter.' );
    }
}
