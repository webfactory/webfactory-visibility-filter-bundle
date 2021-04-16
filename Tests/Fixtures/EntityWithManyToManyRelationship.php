<?php

namespace Webfactory\VisibilityFilterBundle\Tests\Fixtures;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class EntityWithManyToManyRelationship
{
    /**
     * Using many to many relationship to avoid the need of an inversed column in the target entity
     *
     * @ORM\ManyToMany(targetEntity="EntityWithProperVisibilityColumn")
     * @ORM\JoinTable(name="jointable",
     *      joinColumns={@ORM\JoinColumn(name="this", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="that", referencedColumnName="id", unique=true)}
     *      )
     * @var Collection&array
     */
    public $relationship;

    /**
     * @ORM\Id()
     * @ORM\Column()
     *
     * @var int
     */
    public $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
