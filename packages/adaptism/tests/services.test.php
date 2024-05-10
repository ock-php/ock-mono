<?php

/**
 * @file
 */

declare(strict_types=1);

use Donquixote\Adaptism\Tests\Fixtures\AdaptismTestPackage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
  AdaptismTestPackage::configureServices($container);
};
