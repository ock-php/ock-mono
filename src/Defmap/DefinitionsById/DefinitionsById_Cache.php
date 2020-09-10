<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\DefinitionsById;

use Donquixote\Cf\Cache\Offset\CacheOffsetInterface;

class DefinitionsById_Cache implements DefinitionsByIdInterface {

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionsById\DefinitionsByIdInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Cache\Offset\CacheOffsetInterface
   */
  private $cacheOffset;

  /**
   * @param \Donquixote\Cf\Defmap\DefinitionsById\DefinitionsByIdInterface $decorated
   * @param \Donquixote\Cf\Cache\Offset\CacheOffsetInterface $cacheOffset
   */
  public function __construct(
    DefinitionsByIdInterface $decorated,
    CacheOffsetInterface $cacheOffset
  ) {
    $this->decorated = $decorated;
    $this->cacheOffset = $cacheOffset;
  }

  /**
   * @param string $id
   *
   * @return array|null
   */
  public function idGetDefinition($id): ?array {
    $definitions = $this->getDefinitionsById();
    return $definitions[$id] ?? null;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinitionsById(): array {

    if ($this->cacheOffset->getInto($value)) {
      return $value;
    }

    $definitions = $this->decorated->getDefinitionsById();

    $this->cacheOffset->set($definitions);

    return $definitions;
  }
}
