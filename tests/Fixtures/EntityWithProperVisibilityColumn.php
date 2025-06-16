<?php

namespace Webfactory\VisibilityFilterBundle\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;
use Webfactory\VisibilityFilterBundle\Attribute\VisibilityColumn;

#[ORM\Entity]
class EntityWithProperVisibilityColumn
{
    #[ORM\Column(type: 'string')]
    #[VisibilityColumn]
    public string $visibilityColumn;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    public int $id;

    public function __construct(int $id, string $visibilityColumn)
    {
        $this->visibilityColumn = $visibilityColumn;
        $this->id = $id;
    }
}
