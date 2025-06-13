<?php

namespace Webfactory\VisibilityFilterBundle\Tests\Fixtures;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class EntityWithManyToManyRelationship
{
    // Using many to many relationship to avoid the need of an inversed column in the target entity.
    #[ORM\ManyToMany(targetEntity: EntityWithProperVisibilityColumn::class)]
    #[ORM\JoinTable(name: 'jointable')]
    #[ORM\JoinColumn(name: 'this', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'that', referencedColumnName: 'id')]
    public Collection $relationship;

    #[ORM\Id]
    #[ORM\Column]
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
