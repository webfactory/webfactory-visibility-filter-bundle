<?php

namespace Webfactory\VisibilityFilterBundle\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class EntityWithOneToOneRelationship
{
    /**
     * Using many to many relationship to avoid the need of an inversed column in the target entity.
     *
     * @ORM\OneToOne(targetEntity="EntityWithProperVisibilityColumn")
     *
     * @var EntityWithProperVisibilityColumn
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
