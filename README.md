# VisibilityFilterBundle – A centralised approach to Entity visibility

This bundle provides a Doctrine Filter which handles visibility filtering for Entities transparently for a whole
application, removing the need to repeatedly phrase the filtering in every repository method of an Entity. Most notably,
the filtering also applies to Doctrine queries that bypass the repository, like Doctrine Collections for relationships.

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

## Replacing the filter strategy

Overwrite Service-Alias `Webfactory\VisibilityFilterBundle\Filter\FilterStrategy` // TODO
