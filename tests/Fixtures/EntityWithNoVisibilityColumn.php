<?php

namespace Webfactory\VisibilityFilterBundle\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class EntityWithNoVisibilityColumn
{
    /**
     * @ORM\Id()
     *
     * @ORM\Column()
     *
     * @var int
     */
    public $id;
}
