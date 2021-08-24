<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Id;

use Donquixote\ObCK\Defmap\IdToDefinition\IdToDefinitionInterface;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;

class Formula_Id_DefmapKey implements Formula_IdInterface {

  /**
   * @var \Donquixote\ObCK\Defmap\IdToDefinition\IdToDefinitionInterface
   */
  private $definitionMap;

  /**
   * @var string
   */
  private $key;

  /**
   * @param \Donquixote\ObCK\Defmap\IdToDefinition\IdToDefinitionInterface $definitionMap
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
