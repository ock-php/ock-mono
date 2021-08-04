<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionToLabel;

use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;

class DefinitionToLabel implements DefinitionToLabelInterface {

  /**
   * @var string
   */
  private $key;

  /**
   * @return \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabel
   */
  public static function create(): DefinitionToLabel {
    return new self('label');
  }

  /**
   * @return \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabel
   */
  public static function createGroupLabel(): DefinitionToLabel {
    return new self('group_label');
  }

  /**
   * @param string $key
   *   E.g. 'label'.
   */
  public function __construct(string $key) {
    $this->key = $key;
  }

  /**
   * {@inheritdoc}
   */
  public function definitionGetLabel(array $definition, ?string $else): ?TextInterface {
    return $definition[$this->key]
      ?? ($else !== NULL ? Text::s($else) : NULL);
  }
}
