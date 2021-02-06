<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Defmap;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface;

class CfSchema_Defmap implements CfSchema_DefmapInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @var \Donquixote\OCUI\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
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
