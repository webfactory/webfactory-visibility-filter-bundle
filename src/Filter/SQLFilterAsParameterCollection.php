<?php

namespace Webfactory\VisibilityFilterBundle\Filter;

use Doctrine\ORM\Query\Filter\SQLFilter;

class SQLFilterAsParameterCollection implements ParameterCollection
{
    /**
     * @var SQLFilter
     */
    private $sqlFilter;

    public function __construct(SQLFilter $sqlFilter)
    {
        $this->sqlFilter = $sqlFilter;
    }

    final public function setParameter($name, $value, $type = null): void
    {
        $this->sqlFilter->setParameter($name, $value, $type);
    }

    final public function getParameter($name)
    {
        return $this->sqlFilter->getParameter($name);
    }

    final public function hasParameter($name): bool
    {
        return $this->sqlFilter->hasParameter($name);
    }
}
