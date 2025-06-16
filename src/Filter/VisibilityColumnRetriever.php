<?php

namespace Webfactory\VisibilityFilterBundle\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use ReflectionProperty;
use RuntimeException;
use Webfactory\VisibilityFilterBundle\Attribute\VisibilityColumn;

final class VisibilityColumnRetriever
{
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
            if (0 < \count($property->getAttributes(VisibilityColumn::class))) {
                if (null !== $visibilityProperty) {
                    throw new RuntimeException('More than 1 visibility column configured for '.$classMetadata->getName().'. You must only configure 1 visibility column per entity.');
                }

                $visibilityProperty = $property;
            }
        }

        return $visibilityProperty;
    }
}
