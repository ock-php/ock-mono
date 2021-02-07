<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface;
use Donquixote\OCUI\Formula\Definition\Formula_DefinitionInterface;
use Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface;

class FormulaReplacerPartial_Definition implements FormulaReplacerPartialInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface
   */
  private $definitionToFormula;

  /**
   * @param \Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface $definitionToFormula
   */
  public function __construct(DefinitionToFormulaInterface $definitionToFormula) {
    $this->definitionToFormula = $definitionToFormula;
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceFormulaClass(): string {
    return Formula_DefinitionInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function schemaGetReplacement(FormulaInterface $schema, FormulaReplacerInterface $replacer): ?FormulaInterface {

    if (!$schema instanceof Formula_DefinitionInterface) {
      return NULL;
    }

    try {
      $schema = $this->definitionToFormula->definitionGetFormula(
        $schema->getDefinition(),
        $schema->getContext());
    }
    catch (\Exception $e) {
      // @todo Allow throwing exceptions? Log the problem somewhere? BrokenFormula?
      return NULL;
    }

    if (NULL !== $replacement = $replacer->schemaGetReplacement($schema)) {
      $schema = $replacement;
    }

    return $schema;
  }
}
