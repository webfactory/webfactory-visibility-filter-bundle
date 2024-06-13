<?php

namespace Webfactory\VisibilityFilterBundle\Filter;

use Doctrine\DBAL\Types\Types;
use InvalidArgumentException;

interface ParameterCollection
{
    /**
     * Sets a parameter that can be used by the filter.
     *
     * @param string        $name  Name of the parameter.
     * @param string        $value Value of the parameter.
     * @param Types::*|null $type  The parameter type. If specified, the given value will be run through
     *                             the type conversion of this type. This is usually not needed for
     *                             strings and numeric types.
     */
    public function setParameter(string $name, $value, $type = null): void;

    /**
     * Gets a parameter to use in a query.
     *
     * The function is responsible for the right output escaping to use the
     * value in a query.
     *
     * @param string $name Name of the parameter.
     *
     * @return string The SQL escaped parameter to use in a query.
     *
     * @throws InvalidArgumentException
     */
    public function getParameter(string $name);

    /**
     * Checks if a parameter was set for the filter.
     *
     * @param string $name Name of the parameter.
     */
    public function hasParameter(string $name): bool;
}
