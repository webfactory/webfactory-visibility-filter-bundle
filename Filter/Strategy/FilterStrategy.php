<?php

namespace Webfactory\VisibilityFilterBundle\Filter\Strategy;

interface FilterStrategy
{
    public function getFilterSql(string $visibilityFieldAlias): string;
}
