<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionsById;

use Donquixote\OCUI\Cache\Offset\CacheOffsetInterface;

class DefinitionsById_Cache implements DefinitionsByIdInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionsById\DefinitionsByIdInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Cache\Offset\CacheOffsetInterface
   */
  private $cacheOffset;

  /**
   * @param \Donquixote\OCUI\Defmap\DefinitionsById\DefinitionsByIdInterface $decorated
   * @param \Donquixote\OCUI\Cache\Offset\CacheOffsetInterface $cacheOffset
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
  public function idGetDefinition(string $id): ?array {
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
