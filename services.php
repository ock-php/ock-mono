<?php

declare(strict_types = 1);

use Donquixote\Adaptism\AdaptismPackage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
  AdaptismPackage::configureServices($container);
};
