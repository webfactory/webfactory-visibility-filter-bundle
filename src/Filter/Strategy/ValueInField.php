<?php

namespace Webfactory\VisibilityFilterBundle\Filter\Strategy;

use Webfactory\VisibilityFilterBundle\Filter\ParameterCollection;

/**
 * Filters queries so that only entries with a certain value in their visibility field (@see VisibilityColumn) will
 * be retrieved from the database.
 */
final class ValueInField implements FilterStrategy
{
    /**
     * @var string|int|bool
     */
    private $visibleValue;

    /**
     * @param string|int|bool $visibleValue
     */
    public function __construct($visibleValue)
    {
        $this->visibleValue = $visibleValue;
    }

    public function addParameters(ParameterCollection $parameters): void
    {
        // not needed, as $this->visibleValue is immutable
    }

    public function getFilterSql(string $visibilityFieldAlias, ParameterCollection $parameters): string
    {
        return $visibilityFieldAlias.' = '.$this->getSqlLiteral($this->visibleValue);
    }

    /**
     * @param string|int|bool $value
     */
    private function getSqlLiteral($value): string
    {
        if (\is_string($value)) {
            return '"'.$value.'"';
        }

        if (\is_bool($value)) {
            return $value ? '1' : '0';
        }

        return $value;
    }
}
