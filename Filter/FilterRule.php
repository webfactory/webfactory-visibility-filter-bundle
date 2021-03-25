<?php

namespace Webfactory\VisibilityFilterBundle\Filter;

interface FilterRule
{
    public function getFilterSql(string $visibilityFieldAlias): string;
}
