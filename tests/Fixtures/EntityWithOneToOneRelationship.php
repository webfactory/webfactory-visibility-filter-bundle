<?php

namespace Webfactory\VisibilityFilterBundle\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class EntityWithOneToOneRelationship
{
    // fetch="EAGER" prevents a proxy object being put in here, so this will be null when the related entity is not found
    #[ORM\OneToOne(targetEntity: EntityWithProperVisibilityColumn::class, fetch: 'EAGER')]
    public ?EntityWithProperVisibilityColumn $relationship = null;

    #[ORM\Id]
    #[ORM\Column]
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
