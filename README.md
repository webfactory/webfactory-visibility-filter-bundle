# VisibilityFilterBundle – Sichtbarkeitsfilterung leicht gemacht

Dieses Bundle stellt einen Doctrine-Filter bereit, der Sichtbarkeitsfilterung transparent für die gesamte Webanwendung auf SQL-Ebene anwendet, ohne
dass die Sichtbarkeit in den Queries der Repository-Methoden berücksichtigt werden muss. Das gilt insbesondere auch für Collections von Beziehungen
zwischen Entitäten, die von Doctrine ohne übers Repository zu gehen geladen werden.

## Einrichten des Filters im Projekt

Zuerst muss dieses Bundle als Composer-Abhängigkeit hinzugefügt werden.

```shell
composer require webfactory/visibility-filter-bundle
```

Anschließend muss das Bundle als Symfony-Bundle registriert werden.

```php
# src/bundles.php

return [
    # ...
        Webfactory\VisibilityFilterBundle\VisibilityFilterBundle::class => ['all' => true],
    # ...
];
```

Weil Doctrine-Filter Doctrine-Filter sind, kann das Bundle den Filter nicht selbst registrieren; das muss manuell über die Doctrine-Konfiguration des
Projekts gemacht werden:

```yaml
# src/config.yml
doctrine:
    orm:
        filters:
            visibility: Webfactory\VisibilityFilterBundle\Filter\DoctrineSQLFilter
```

Wichtig: Der Key des Filters muss dem Wert der Konstante `DoctrineSQLFilter::NAME` ensprechen (`'visibility'`), sonst wird der Filter bei Requests
nicht aktiviert.

## Filter-Strategie ändern

Überschreibe Service-Alias `Webfactory\VisibilityFilterBundle\Filter\FilterStrategy`
