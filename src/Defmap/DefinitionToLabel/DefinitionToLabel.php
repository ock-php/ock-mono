<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\DefinitionToLabel;

class DefinitionToLabel implements DefinitionToLabelInterface {

  /**
   * @var string
   */
  private $key;

  /**
   * @return \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabel
   */
  public static function create(): DefinitionToLabel {
    return new self('label');
  }

  /**
   * @return \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabel
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
  public function definitionGetLabel(array $definition, ?string $else): string {
    return $definition[$this->key] ?? $else;
  }
}
