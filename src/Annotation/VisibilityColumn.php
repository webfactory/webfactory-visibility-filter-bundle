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
 *
 * @deprecated, to be replaced by attribute-based configuration
 */
final class VisibilityColumn
{
    public function __construct(array $parameters)
    {
        trigger_deprecation(
            'webfactory/visibility-filter-bundle',
            '1.5.0',
            'The %s annotation has been deprecated, use the %s attribute instead.',
            __CLASS__,
            \Webfactory\VisibilityFilterBundle\Attribute\VisibilityColumn::class
        );
    }
}
