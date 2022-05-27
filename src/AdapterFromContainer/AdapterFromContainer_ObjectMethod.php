<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterFromContainer;

use Donquixote\Adaptism\Exception\AdapterException;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_Callback;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface;
use Donquixote\Adaptism\Util\MessageUtil;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class AdapterFromContainer_ObjectMethod implements AdapterFromContainerInterface {

  /**
   * @var callable
   */
  private readonly mixed $factoryCallback;

  /**
   * Constructor.
   *
   * @param callable $factoryCallback
   * @param string $method
   * @param bool $hasResultTypeParameter
   * @param bool $hasUniversalAdapterParameter
   * @param list<string> $factoryServiceIds
   */
  public function __construct(
    callable $factoryCallback,
    private readonly string $method,
    private readonly bool $hasResultTypeParameter,
    private readonly bool $hasUniversalAdapterParameter,
    private readonly array $factoryServiceIds,
  ) {
    $this->factoryCallback = $factoryCallback;
  }

  /**
   * {@inheritdoc}
   */
  public function createAdapter(ContainerInterface $container): SpecificAdapterInterface {
    $args = [];
    foreach ($this->factoryServiceIds as $id) {
      try {
        $args[] = $container->get($id);
      }
      catch (ContainerExceptionInterface $e) {
        throw new AdapterException(sprintf(
          'Missing service %s for callback %s: %s.',
          var_export($id, true),
          MessageUtil::formatValue($this->factoryCallback),
          $e->getMessage(),
        ), 0, $e);
      }
    }
    $object = ($this->factoryCallback)(...$args);
    if (!\is_callable([$object, $this->method])) {
      throw new AdapterException(\sprintf(
        'Expected an object with ->%s() method, found %s, returned from %s',
        $this->method,
        MessageUtil::formatValue($object),
        MessageUtil::formatValue($this->factoryCallback),
      ));
    }
    return new SpecificAdapter_Callback(
      [$object, $this->method],
      $this->hasResultTypeParameter,
      $this->hasUniversalAdapterParameter,
      [],
    );
  }

}
