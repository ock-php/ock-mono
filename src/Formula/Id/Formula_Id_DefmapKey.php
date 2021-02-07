<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Id;

use Donquixote\OCUI\Defmap\IdToDefinition\IdToDefinitionInterface;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;

class Formula_Id_DefmapKey implements Formula_IdInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\IdToDefinition\IdToDefinitionInterface
   */
  private $definitionMap;

  /**
   * @var string
   */
  private $key;

  /**
   * @param \Donquixote\OCUI\Defmap\IdToDefinition\IdToDefinitionInterface $definitionMap
   * @param string $key
   */
  public function __construct(IdToDefinitionInterface $definitionMap, string $key) {
    $this->definitionMap = $definitionMap;
    $this->key = $key;
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {

    if (NULL === $definition = $this->definitionMap->idGetDefinition($id)) {
      return FALSE;
    }

    return !empty($definition[$this->key]);
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {
    return Text::s($id);
  }
}
