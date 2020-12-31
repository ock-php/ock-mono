<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\TypeToDefmap;

use Donquixote\Cf\Cache\Prefix\CachePrefixInterface;
use Donquixote\Cf\Defmap\DefinitionMap\DefinitionMap_Buffer;
use Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\Cf\Defmap\DefinitionsById\DefinitionsById_Cache;
use Donquixote\Cf\Defmap\DefinitionsById\DefinitionsById_FromType;
use Donquixote\Cf\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface;

class TypeToDefmap_Cache implements TypeToDefmapInterface {

  /**
   * @var \Donquixote\Cf\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface
   */
  private $typeToDefinitionsbyid;

  /**
   * @var \Donquixote\Cf\Cache\Prefix\CachePrefixInterface
   */
  private $cachePrefix;

  /**
   * @param \Donquixote\Cf\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface $typeToDefinitionsbyid
   * @param \Donquixote\Cf\Cache\Prefix\CachePrefixInterface|null $cachePrefix
   *   A prefix to prepend to the cache id, or NULL to have no cache.
   *   If specified, it should include the langcode.
   */
  public function __construct(
    TypeToDefinitionsbyidInterface $typeToDefinitionsbyid,
    CachePrefixInterface $cachePrefix = NULL
  ) {
    $this->typeToDefinitionsbyid = $typeToDefinitionsbyid;
    $this->cachePrefix = $cachePrefix;
  }

  /**
   * {@inheritdoc}
   */
  public function typeGetDefmap(string $type): DefinitionMapInterface {
    $definitionsById = new DefinitionsById_FromType($this->typeToDefinitionsbyid, $type);
    if (NULL !== $this->cachePrefix) {
      $definitionsById = new DefinitionsById_Cache(
        $definitionsById,
        $this->cachePrefix->getOffset($type));
    }
    return new DefinitionMap_Buffer($definitionsById);
  }
}
