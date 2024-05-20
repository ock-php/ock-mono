<?php

declare(strict_types=1);

namespace Ock\Adaptism\AdapterFromContainer;

use Ock\Adaptism\Exception\AdapterException;
use Ock\Adaptism\SpecificAdapter\SpecificAdapter_Callback;
use Ock\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Ock\Helpers\Util\MessageUtil;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class AdapterFromContainer_Callback implements AdapterFromContainerInterface {

  /**
   * @var callable
   */
  private readonly mixed $callback;

  /**
   * Constructor.
   *
   * @param callable $callback
   * @param bool $hasResultTypeParameter
   * @param bool $hasUniversalAdapterParameter
   * @param list<string> $serviceIds
   */
  public function __construct(
    callable $callback,
    private readonly bool $hasResultTypeParameter,
    private readonly bool $hasUniversalAdapterParameter,
    private readonly array $serviceIds,
  ) {
    $this->callback = $callback;
  }

  /**
   * {@inheritdoc}
   */
  public function createAdapter(ContainerInterface $container): SpecificAdapterInterface {
    $args = [];
    foreach ($this->serviceIds as $id) {
      if ($container instanceof $id) {
        $args[] = $container;
        continue;
      }
      try {
        $arg = $container->get($id);
      }
      catch (ContainerExceptionInterface $e) {
        throw new AdapterException(sprintf(
          'Service %s needed for %s: %s',
          $id,
          MessageUtil::formatValue($this->callback),
          $e->getMessage(),
        ), 0, $e);
      }
      if ($arg === null) {
        throw new AdapterException(sprintf(
          'Service %s needed for %s is NULL.',
          $id,
          MessageUtil::formatValue($this->callback),
        ));
      }
      $args[] = $arg;
    }
    return new SpecificAdapter_Callback(
      $this->callback,
      $this->hasResultTypeParameter,
      $this->hasUniversalAdapterParameter,
      $args,
    );
  }

}
