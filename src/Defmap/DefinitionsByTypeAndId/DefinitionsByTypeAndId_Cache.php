<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\DefinitionsByTypeAndId;

use Donquixote\ObCK\Cache\Offset\CacheOffsetInterface;

class DefinitionsByTypeAndId_Cache implements DefinitionsByTypeAndIdInterface {

  /**
   * @var \Donquixote\ObCK\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\ObCK\Cache\Offset\CacheOffsetInterface
   */
  private $cacheOffset;

  /**
   * @param \Donquixote\ObCK\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface $decorated
   * @param \Donquixote\ObCK\Cache\Offset\CacheOffsetInterface $cacheOffset
   */
  public function __construct(
    DefinitionsByTypeAndIdInterface $decorated,
    CacheOffsetInterface $cacheOffset
  ) {
    $this->decorated = $decorated;
    $this->cacheOffset = $cacheOffset;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinitionsByTypeAndId(): array {

    if ($this->cacheOffset->getInto($value)) {
      return $value;
    }

    $definitions = $this->decorated->getDefinitionsByTypeAndId();

    $this->cacheOffset->set($definitions);

    return $definitions;
  }
}
