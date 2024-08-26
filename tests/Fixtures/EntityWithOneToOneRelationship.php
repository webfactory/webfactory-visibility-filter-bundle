<?php

namespace Webfactory\VisibilityFilterBundle\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class EntityWithOneToOneRelationship
{
    /**
     * @ORM\OneToOne(targetEntity="EntityWithProperVisibilityColumn", fetch="EAGER") fetch="EAGER" prevents a proxy object being put in here, so this will be null when the related entity is not found
     *
     * @var EntityWithProperVisibilityColumn
     */
    public $relationship;

    /**
     * @ORM\Id()
     *
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
