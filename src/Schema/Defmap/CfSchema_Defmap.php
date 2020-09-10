<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Defmap;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface;

class CfSchema_Defmap implements CfSchema_DefmapInterface {

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   */
  public function __construct(DefinitionMapInterface $definitionMap, CfContextInterface $context = NULL) {
    $this->definitionMap = $definitionMap;
    $this->context = $context;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinitionMap(): DefinitionMapInterface {
    return $this->definitionMap;
  }

  /**
   * {@inheritdoc}
   */
  public function getContext(): ?CfContextInterface {
    return $this->context;
  }
}
