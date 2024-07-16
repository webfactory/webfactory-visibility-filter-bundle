<?php

namespace Webfactory\VisibilityFilterBundle\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;
use Webfactory\VisibilityFilterBundle\Annotation\VisibilityColumn;

/**
 * @ORM\Entity()
 */
class EntityWithProperVisibilityColumn
{
    /**
     * @VisibilityColumn()
     * @ORM\Column(type="string")
     *
     * @var string
     */
    public $visibilityColumn;

    /**
     * @ORM\Id()
     * @ORM\Column()
     *
     * @var int
     */
    public $id;

    public function __construct(int $id, string $visibilityColumn)
    {
        $this->visibilityColumn = $visibilityColumn;
        $this->id = $id;
    }
}
