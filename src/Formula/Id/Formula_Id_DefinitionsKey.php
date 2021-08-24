<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Id;

use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;

class Formula_Id_DefinitionsKey implements Formula_IdInterface {

  /**
   * @var array[]
   */
  private $definitions;

  /**
   * @var string
   */
  private $key;

  /**
   * @param array[] $definitions
   * @param string $key
   */
  public function __construct(array $definitions, string $key) {
    $this->definitions = $definitions;
    $this->key = $key;
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {
    return !empty($this->definitions[$id][$this->key]);
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {
    // @todo What about ->definitionGetLabel() ?
    return Text::s($id);
  }
}
