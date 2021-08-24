<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaReplacer\Partial;

class FormulaReplacerPartial_IfaceDefinitions extends FormulaReplacerPartial_IfaceDefinitionsBase {

  /**
   * @var array[][]
   */
  private $definitionss;

  /**
   * @param array[][] $definitionss
   */
  public function __construct(array $definitionss) {
    $this->definitionss = $definitionss;
  }

  /**
   * @param string $type
   *
   * @return array[]
   */
  protected function typeGetDefinitions(string $type): array {

    return $this->definitionss[$type] ?? [];
  }
}
