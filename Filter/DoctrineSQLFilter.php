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
    private $filterRule;

    /**
     * @var VisibilityColumnRetriever
     */
    private $visiblityColumnRetriever;

    public function setFilterRule(FilterStrategy $filterRule): void
    {
        $this->filterRule = $filterRule;
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

        return $this->filterRule->getFilterSql($targetTableAlias.'.'.$visibilityColumn);
    }

    private function assertSetUpCorrectly(): void
    {
        if ($this->filterRule !== null && $this->visiblityColumnRetriever !== null) {
            return;
        }

        throw new RuntimeException('Filter not set up correctly: You need to inject a FilterRule and a VisibilityColumnRetriever via setter before using the filter.' );
    }
}
