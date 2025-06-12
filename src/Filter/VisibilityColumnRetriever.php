<?php

namespace Webfactory\VisibilityFilterBundle\Filter;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Mapping\ClassMetadata;
use ReflectionProperty;
use RuntimeException;
use Webfactory\VisibilityFilterBundle\Annotation\VisibilityColumn;

final class VisibilityColumnRetriever
{
    /**
     * @var Reader
     */
    private $annotationReader;

    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function getVisibilityColumnName(ClassMetadata $classMetadata): ?string
    {
        $property = $this->getVisibilityProperty($classMetadata);

        if (null === $property) {
            return null;
        }

        if (!\array_key_exists($property->getName(), $classMetadata->fieldMappings)) {
            throw new RuntimeException('Property '.$property->getName().' of class '.$classMetadata->getName().' configured as Visibility Column is not mapped by Doctrine.');
        }

        return $classMetadata->getColumnName($property->getName());
    }

    private function getVisibilityProperty(ClassMetadata $classMetadata): ?ReflectionProperty
    {
        $visibilityProperty = null;

        foreach ($classMetadata->getReflectionClass()->getProperties() as $property) {
            if (\PHP_MAJOR_VERSION >= 8) {
                if (0 < count($property->getAttributes(\Webfactory\VisibilityFilterBundle\Attribute\VisibilityColumn::class))) {
                    $visibilityProperty = $property;
                }
            }

            if (null !== $this->annotationReader->getPropertyAnnotation($property, VisibilityColumn::class)) {
                trigger_deprecation(
                    'webfactory/visibility-filter-bundle',
                    '1.5.0',
                    'Configuring webfactory/visibility-filter-bundle with annotations is deprecated, use attributes instead.'
                );

                if (null !== $visibilityProperty) {
                    throw new RuntimeException('More than 1 visibility column configured for '.$classMetadata->getName().'. You must only configure 1 visibility column per entity.');
                }

                $visibilityProperty = $property;
            }
        }

        return $visibilityProperty;
    }
}
