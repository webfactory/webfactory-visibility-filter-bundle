<?php

namespace Webfactory\VisibilityFilterBundle\Filter;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Mapping\ClassMetadata;
use ReflectionProperty;
use Webfactory\VisibilityFilterBundle\Annotation\VisibilityColumn;

class VisibilityColumnRetriever
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

        if ($property === null) {
            return null;
        }

        return $classMetadata->getColumnName($property->getName());
    }

    private function getVisibilityProperty(ClassMetadata $classMetadata): ?ReflectionProperty
    {
        foreach ($classMetadata->getReflectionProperties() as $property) {
            if ($this->annotationReader->getPropertyAnnotation($property, VisibilityColumn::class)) {
                return $property;
            }
        }

        return null;
    }
}
