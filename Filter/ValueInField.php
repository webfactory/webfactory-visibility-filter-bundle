<?php

namespace Webfactory\VisibilityFilterBundle\Filter;

/**
 * Filters queries so that only entries with a certain value in their visibility field (@see VisibilityColumn) will
 * be retrieved from the database.
 */
class ValueInField implements FilterStrategy
{
    /**
     * @var string
     */
    private $visibleValue;

    public function __construct(string $visibleValue)
    {
        $this->visibleValue = $visibleValue;
    }

    public function getFilterSql(string $visibilityFieldAlias): string
    {
        return $visibilityFieldAlias.' = "'.$this->visibleValue.'"';
    }
}
