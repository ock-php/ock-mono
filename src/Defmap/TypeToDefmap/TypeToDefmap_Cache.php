<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\TypeToDefmap;

use Donquixote\ObCK\Cache\Prefix\CachePrefixInterface;
use Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMap_Buffer;
use Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\ObCK\Defmap\DefinitionsById\DefinitionsById_Cache;
use Donquixote\ObCK\Defmap\DefinitionsById\DefinitionsById_FromType;
use Donquixote\ObCK\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface;

class TypeToDefmap_Cache implements TypeToDefmapInterface {

  /**
   * @var \Donquixote\ObCK\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface
   */
  private $typeToDefinitionsbyid;

  /**
   * @var \Donquixote\ObCK\Cache\Prefix\CachePrefixInterface
   */
  private $cachePrefix;

  /**
   * @param \Donquixote\ObCK\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface $typeToDefinitionsbyid
   * @param \Donquixote\ObCK\Cache\Prefix\CachePrefixInterface|null $cachePrefix
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
