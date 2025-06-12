<?php

namespace Webfactory\VisibilityFilterBundle\Attribute;

use Attribute;
use Webfactory\VisibilityFilterBundle\Filter\Strategy\FilterStrategy;

/**
 * This attribute marks a field of an entity as the field containing visibility information (e.g. "y" for visible,
 * "n" for invisible, or a more complicated system). If the VisibilityFilterBundle is set up, it will be interpreted
 * by an implementation of @see FilterStrategy.
 *
 * For more information on visibility filtering, consider reading the README.md file of this bundle.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class VisibilityColumn
{
}
