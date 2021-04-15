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
        foreach ($classMetadata->getReflectionClass()->getProperties() as $property) {
            if ($this->annotationReader->getPropertyAnnotation($property, VisibilityColumn::class)) {
                return $property;
            }
        }

        return null;
    }
}
