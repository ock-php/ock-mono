<?php

declare(strict_types=1);

namespace Ock\Adaptism\AdapterFromContainer;

use Ock\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Psr\Container\ContainerInterface;

interface AdapterFromContainerInterface {

  /**
   * @param \Psr\Container\ContainerInterface $container
   *
   * @return \Ock\Adaptism\SpecificAdapter\SpecificAdapterInterface
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
   */
  public function createAdapter(ContainerInterface $container): SpecificAdapterInterface;

}
