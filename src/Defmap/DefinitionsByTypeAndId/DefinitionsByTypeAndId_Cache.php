<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\DefinitionsByTypeAndId;

use Donquixote\Cf\Cache\Offset\CacheOffsetInterface;

class DefinitionsByTypeAndId_Cache implements DefinitionsByTypeAndIdInterface {

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Cache\Offset\CacheOffsetInterface
   */
  private $cacheOffset;

  /**
   * @param \Donquixote\Cf\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface $decorated
   * @param \Donquixote\Cf\Cache\Offset\CacheOffsetInterface $cacheOffset
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
