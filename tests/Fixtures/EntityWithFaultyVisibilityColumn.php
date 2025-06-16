<?php

namespace Webfactory\VisibilityFilterBundle\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;
use Webfactory\VisibilityFilterBundle\Attribute\VisibilityColumn;

#[ORM\Entity]
class EntityWithFaultyVisibilityColumn
{
    // (No Doctrine Column Annotation)
    #[VisibilityColumn]
    public string $visibilityColumn;

    #[ORM\Id]
    #[ORM\Column]
    public int $id;
}
