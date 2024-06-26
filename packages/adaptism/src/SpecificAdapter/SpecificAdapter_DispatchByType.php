<?php
declare(strict_types=1);

namespace Ock\Adaptism\SpecificAdapter;

use Ock\Adaptism\AdapterMap\AdapterMapInterface;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\Helpers\Util\MessageUtil;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias]
class SpecificAdapter_DispatchByType implements SpecificAdapterInterface {

  /**
   * Constructor.
   *
   * @param \Ock\Adaptism\AdapterMap\AdapterMapInterface $adapterMap
   */
  public function __construct(
    private readonly AdapterMapInterface $adapterMap,
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
      $resultType,
    );
    foreach ($adapters as $adapter) {
      $candidate = $adapter->adapt($adaptee, $resultType, $universalAdapter);
      if ($candidate === null) {
        continue;
      }
      assert($candidate instanceof $resultType, sprintf(
        'Misbehaving adapter %s: Expected %s object or NULL, found %s, for %s .',
        MessageUtil::formatValue($adapter),
        $resultType,
        MessageUtil::formatValue($candidate),
        MessageUtil::formatValue($adaptee),
      ));
      return $candidate;
    }

    // No successful adapter.
    return NULL;
  }

}
