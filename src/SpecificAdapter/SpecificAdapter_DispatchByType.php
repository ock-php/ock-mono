<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\AdapterMap\AdapterMapInterface;
use Donquixote\Adaptism\Exception\MisbehavingAdapterException;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

class SpecificAdapter_DispatchByType implements SpecificAdapterInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Adaptism\AdapterMap\AdapterMapInterface $adapterMap
   */
  public function __construct(
    private AdapterMapInterface $adapterMap,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function adapt(
    object $adaptee,
    string $resultType,
    UniversalAdapterInterface $universalAdapter
  ): ?object {
    $adapters = $this->adapterMap->getSuitableAdapters(
      \get_class($adaptee),
      $resultType);

    foreach ($adapters as $adapter) {
      $candidate = $adapter->adapt($adaptee, $resultType, $universalAdapter);
      if ($candidate instanceof $resultType) {
        return $candidate;
      }
      if ($candidate !== null) {
        throw new MisbehavingAdapterException(\sprintf(
          'Expected %s object, found %s.',
          $resultType,
          \is_object($candidate)
            ? \get_class($candidate) . ' object'
            : \gettype($candidate) . ' value'
        ));
      }
    }

    // No successful adapter.
    return NULL;
  }

}
