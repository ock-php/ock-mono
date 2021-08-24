<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Defmap;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface;

class Formula_Defmap implements Formula_DefmapInterface {

  /**
   * @var \Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @var \Donquixote\ObCK\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
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
