<?php

namespace Webfactory\VisibilityFilterBundle\Filter;

class HatBestimmtenWertImSichtbarkeitsfeld implements FilterStrategy
{
    /**
     * @var string
     */
    private $sichtbarWert;

    public function __construct(string $sichtbarWert)
    {
        $this->sichtbarWert = $sichtbarWert;
    }

    public function getFilterSql(string $visibilityFieldAlias): string
    {
        return $visibilityFieldAlias.' = "'.$this->sichtbarWert.'"';
    }
}
