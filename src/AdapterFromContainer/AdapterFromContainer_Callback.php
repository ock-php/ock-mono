<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterFromContainer;

use Donquixote\Adaptism\Exception\AdapterException;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_Callback;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class AdapterFromContainer_Callback implements AdapterFromContainerInterface {

  /**
   * Constructor.
   *
   * @param callable $callback
   * @param bool $hasResultTypeParameter
   * @param bool $hasUniversalAdapterParameter
   * @param list<string> $serviceIds
   *
   * @noinspection PhpDocSignatureInspection
   */
  public function __construct(
    private readonly array|object|string $callback,
    private readonly bool $hasResultTypeParameter,
    private readonly bool $hasUniversalAdapterParameter,
    private readonly array $serviceIds,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function createAdapter(ContainerInterface $container): SpecificAdapterInterface {
    $args = [];
    foreach ($this->serviceIds as $id) {
      try {
        $args[] = $container->get($id);
      }
      catch (ContainerExceptionInterface $e) {
        throw new AdapterException($e->getMessage(), 0, $e);
      }
    }
    return new SpecificAdapter_Callback(
      $this->callback,
      $this->hasResultTypeParameter,
      $this->hasUniversalAdapterParameter,
      $args,
    );
  }

}
