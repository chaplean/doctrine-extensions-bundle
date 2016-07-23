Getting Started With Chaplean Doctrine Extensions Bundle
========================================================

# Prerequisites

This version of the bundle requires Symfony 2.8+.

# Installation

## 1. Composer

```
composer require chaplean/doctrine-extensions-bundle
```

## 2. AppKernel.php

Add
```php
            new Chaplean\Bundle\DoctrineExtensionsBundle\ChapleanDoctrineExtensionsBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
```

## 3. app/config/config.yml

##### Optional : mapping for loggable/translatable/tree

Add lines
```yml
doctrine:
    orm:
        mappings:
            translatable:
                type: annotation
                alias: Gedmo
                prefix: Gedmo\Translatable\Entity
                # make sure vendor library location is correct
                dir: '%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Translatable/Entity'
            loggable:
                type: annotation
                alias: Gedmo
                prefix: Gedmo\Loggable\Entity
                dir: '%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Loggable/Entity'
            tree:
                type: annotation
                alias: Gedmo
                prefix: Gedmo\Tree\Entity
                dir: '%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Tree/Entity'
```
