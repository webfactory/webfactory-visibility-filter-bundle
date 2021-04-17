<?php

namespace Webfactory\VisibilityFilterBundle\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;
use Webfactory\VisibilityFilterBundle\Annotation\VisibilityColumn;

/**
 * @ORM\Entity()
 */
class EntityWithNoVisibilityColumn
{
    /**
     * @ORM\Id()
     * @ORM\Column()
     *
     * @var int
     */
    public $id;
}
