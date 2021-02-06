<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\TypeToDefmap;

use Donquixote\OCUI\Cache\Prefix\CachePrefixInterface;
use Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMap_Buffer;
use Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\OCUI\Defmap\DefinitionsById\DefinitionsById_Cache;
use Donquixote\OCUI\Defmap\DefinitionsById\DefinitionsById_FromType;
use Donquixote\OCUI\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface;

class TypeToDefmap_Cache implements TypeToDefmapInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface
   */
  private $typeToDefinitionsbyid;

  /**
   * @var \Donquixote\OCUI\Cache\Prefix\CachePrefixInterface
   */
  private $cachePrefix;

  /**
   * @param \Donquixote\OCUI\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface $typeToDefinitionsbyid
   * @param \Donquixote\OCUI\Cache\Prefix\CachePrefixInterface|null $cachePrefix
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
