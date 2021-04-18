<?php

namespace Webfactory\VisibilityFilterBundle\Annotation;

use Webfactory\VisibilityFilterBundle\Filter\Strategy\FilterStrategy;

/**
 * This annotation marks a field of an entity as the field containing visibility information (e.g. "y" for visible,
 * "n" for invisible, or a more complicated system). If the VisibilityFilterBundle is set up, it will be interpreted
 * by an implementation of @see FilterStrategy.
 *
 * For more information on visibility filtering, consider reading the README.md file of this bundle.
 *
 * @Annotation
 * @Target("PROPERTY")
 */
final class VisibilityColumn
{
}
