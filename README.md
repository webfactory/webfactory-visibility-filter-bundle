# VisibilityFilterBundle – A centralised approach to Entity visibility

This bundle provides a Doctrine Filter which handles visibility filtering for Entities transparently for a whole
application, removing the need to repeatedly phrase the filtering in every repository method of an Entity. Most notably,
the filtering also applies to Doctrine queries that bypass the repository, like relationships declared in the entity
mapping.

## Getting started

First, you need to declare this bundle as a composer dependency.

```shell
composer require webfactory/visibility-filter-bundle
```

Next, the bundle needs to be registered to Symfony.

```php
# src/bundles.php

return [
    # ...
        Webfactory\VisibilityFilterBundle\VisibilityFilterBundle::class => ['all' => true],
    # ...
];
```

The filter class needs to be registered manually, as Symfony bundles cannot do this by themselves.

```yaml
# src/config.yml
doctrine:
    orm:
        filters:
            visibility: Webfactory\VisibilityFilterBundle\Filter\StrategyConsideringSQLFilter
```

Important: The YAML key of the filter needs to be the same as the constant `StrategyConsideringSQLFilter::NAME` (`'visibility'`),
otherwise the filter won't be activated on requests.

## Configuring the visibility column

This bundle assumes that the visibility determination is going to be based on a specific field in the Entity containing
visibility information; e.g. functioning as a "visibility switch" containing "yes" or "no" or containing a visibility
grade on a scale, based on which the visibility of the object will be determined.

**Currently, only entities that have a visibility column configured will be filtered at all.**

All you need to configure on your entity is *which* of its fields will be the one with the visibility information.
You can do that by Adding the `VisibilityColumn()` annotation to that field.

```php
/**
 * @ORM\Entity()
 */
use Doctrine\ORM\Mapping as ORM;
use Webfactory\VisibilityFilterBundle\Annotation\VisibilityColumn;

class EntityWithVisibilityColumn
{
    // ...
    /**
     * @VisibilityColumn()
     *
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $visibilityColumn;
    // ...
}
```

Please note that configuring more than one field as visibility column will throw an exception.

## Replacing the filter strategy

Overwrite Service-Alias `Webfactory\VisibilityFilterBundle\Filter\FilterStrategy` // TODO
