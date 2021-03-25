<?php

namespace Webfactory\VisibilityFilterBundle\Filter;

class ZeigeEntitiesMitYImSichtbarkeitsfeld implements FilterRule
{
    public function getFilterSql(string $visibilityFieldAlias): string
    {
        return $visibilityFieldAlias.' = "y"';
    }
}
