# Twig View

[![Build Status](https://travis-ci.org/bittyphp/view-twig.svg?branch=master)](https://travis-ci.org/bittyphp/view-twig)
[![Codacy Badge](https://api.codacy.com/project/badge/Coverage/c1ba5f9952b4438f9583d2da0dd19167)](https://www.codacy.com/app/bittyphp/view-twig)
[![PHPStan Enabled](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)
[![Mutation Score](https://badge.stryker-mutator.io/github.com/bittyphp/view-twig/master)](https://infection.github.io)
[![Total Downloads](https://poser.pugx.org/bittyphp/view-twig/downloads)](https://packagist.org/packages/bittyphp/view-twig)
[![License](https://poser.pugx.org/bittyphp/view-twig/license)](https://packagist.org/packages/bittyphp/view-twig)

A [Twig](https://twig.symfony.com/) template view for Bitty.

## Installation

It's best to install using [Composer](https://getcomposer.org/).

```sh
$ composer require bittyphp/view-twig
```

## Setup

You can use any valid [Twig Environment](https://twig.symfony.com/doc/2.x/api.html#environment-options) options.

### Basic Usage

```php
<?php

require(dirname(__DIR__).'/vendor/autoload.php');

use Bitty\Application;
use Bitty\View\Twig;

$app = new Application();

$app->getContainer()->set('view', function () {
    return new Twig(dirname(__DIR__).'/templates/', $options);
});

$app->get('/', function () {
    return $this->get('view')->renderResponse('index.twig', ['name' => 'Joe Schmoe']);
});

$app->run();

```

### Multiple Template Paths

If you have templates split across multiple directories, you can pass in an array with the paths to load from.

```php
<?php

use Bitty\View\Twig;

$twig = new Twig(
    [
        'templates/',
        'views/',
    ]
);

$twig->render('foo.twig');
// Looks for the following templates until it finds one:
// 'templates/foo.twig'
// 'views/foo.twig'

```

### Namespaced Templates

You can also add namespaces to the template directories. If you have multiple templates with the same name but in different directories, this makes it really easy to reference a particular template.

```php
<?php

use Bitty\View\Twig;

$twig = new Twig(
    [
        'admin' => 'templates/admin/',
        'public' => 'templates/public/',
    ]
);

$twig->render('@admin/index.twig');

```

## Adding Extensions

One of the great things about Twig is that you can easily extend it to add you own custom functionality. This view would not be complete without allowing access to that ability.

```php
<?php

use Bitty\View\Twig;

$twig = new Twig(...);

/** @var Twig_ExtensionInterface */
$extension = ...;

$twig->addExtension($extension);

```

## Advanced

If you need to do any advanced customization, you can access the Twig environment and loader directly at any time.

```php
<?php

use Bitty\View\Twig;

$twig = new Twig(...);

/** @var Twig_Environment */
$environment = $twig->getEnvironment();

/** @var Twig_LoaderInterface */
$loader = $twig->getLoader();

```
