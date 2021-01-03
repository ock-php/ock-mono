<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Id;

use Donquixote\Cf\Defmap\IdToDefinition\IdToDefinitionInterface;
use Donquixote\Cf\Text\Text;
use Donquixote\Cf\Text\TextInterface;

class CfSchema_Id_DefmapKey implements CfSchema_IdInterface {

  /**
   * @var \Donquixote\Cf\Defmap\IdToDefinition\IdToDefinitionInterface
   */
  private $definitionMap;

  /**
   * @var string
   */
  private $key;

  /**
   * @param \Donquixote\Cf\Defmap\IdToDefinition\IdToDefinitionInterface $definitionMap
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
