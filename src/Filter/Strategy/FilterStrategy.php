<?php

namespace Webfactory\VisibilityFilterBundle\Filter\Strategy;

/**
 * Implement this to apply custom filtering logic to your project.
 */
interface FilterStrategy
{
    /**
     * @param string $visibilityFieldAlias SQL alias for the visibility field of the requested entity
     *
     * @return string A string of SQL that will be included in the WHERE clause to any query requesting an entity with a visibility field
     */
    public function getFilterSql(string $visibilityFieldAlias): string;
}
