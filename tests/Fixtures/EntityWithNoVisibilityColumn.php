<?php

namespace Webfactory\VisibilityFilterBundle\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class EntityWithNoVisibilityColumn
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
