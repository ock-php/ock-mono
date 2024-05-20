<?php

declare(strict_types = 1);

use Ock\Ock\OckPackage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
  OckPackage::configureServices($container);
};
