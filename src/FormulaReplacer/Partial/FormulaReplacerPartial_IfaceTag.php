<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContextInterface;
use Donquixote\OCUI\Formula\Neutral\Formula_Neutral_IfaceTransformed;
use Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface;

class FormulaReplacerPartial_IfaceTag extends FormulaReplacerPartial_IfaceBase {

  /**
   * @var \Donquixote\OCUI\FormulaReplacer\Partial\FormulaReplacerPartialInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\OCUI\FormulaReplacer\Partial\FormulaReplacerPartialInterface $decorated
   */
  public function __construct(FormulaReplacerPartialInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param \Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContextInterface $ifaceFormula
   * @param \Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  protected function schemaDoGetReplacement(
    Formula_IfaceWithContextInterface $ifaceFormula,
    FormulaReplacerInterface $replacer
  ): ?FormulaInterface {

    if (NULL === $schema = $this->decorated->schemaGetReplacement($ifaceFormula, $replacer)) {
      // @todo Tag this one as well?
      return NULL;
      # $schema = $ifaceFormula;
    }

    $schema = new Formula_Neutral_IfaceTransformed(
      $schema,
      $ifaceFormula->getInterface(),
      $ifaceFormula->getContext());

    return $schema;
  }
}
