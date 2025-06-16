<?php

namespace Webfactory\VisibilityFilterBundle\Filter\Strategy;

use Webfactory\VisibilityFilterBundle\Filter\ParameterCollection;

/**
 * Implement this to apply custom filtering logic to your project.
 */
interface FilterStrategy
{
    /**
     * Adds all parameters to the collection that affect the resulting filter SQL string. If the filter SQL depends on
     * factors other than parameters added to the collection, the cache won't be invalidated, which will have undesired
     * consequences!
     *
     * So please add all parameters to the collection.
     */
    public function addParameters(ParameterCollection $parameters): void;

    /**
     * @param string $visibilityFieldAlias SQL alias for the visibility field of the requested entity
     *
     * @return string A string of SQL that will be included in the WHERE clause to any query requesting an entity with a visibility field
     */
    public function getFilterSql(string $visibilityFieldAlias, ParameterCollection $parameters): string;
}
