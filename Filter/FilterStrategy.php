<?php

namespace Webfactory\VisibilityFilterBundle\Filter;

interface FilterStrategy
{
    public function getFilterSql(string $visibilityFieldAlias): string;
}
