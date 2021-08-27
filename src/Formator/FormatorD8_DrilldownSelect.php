<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\DrilldownKeysHelper\DrilldownKeysHelper;
use Donquixote\ObCK\DrilldownKeysHelper\DrilldownKeysHelperInterface;
use Donquixote\ObCK\Exception\FormulaToAnythingException;
use Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\ObCK\Formula\Select\Formula_SelectInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;
use Donquixote\ObCK\Optionlessness\OptionlessnessInterface;

class FormatorD8_DrilldownSelect extends FormatorD8_DrilldownSelectBase {

  /**
   * @var \Donquixote\ObCK\IdToFormula\IdToFormulaInterface
   */
  private $idToFormula;

  /**
   * @var \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface
   */
  private $formulaToAnything;

  /**
   * @var \Drupal\cu\Formator\FormatorD8Interface[]|false[]
   */
  private $formators = [];

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface $drilldown
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return self|null
   */
  public static function create(Formula_DrilldownInterface $drilldown, FormulaToAnythingInterface $formulaToAnything): ?self {

    $idFormula = $drilldown->getIdFormula();

    if (!$idFormula instanceof Formula_SelectInterface) {
      // Not supported. Write your own formator.
      return NULL;
    }

    $idToFormula = $drilldown->getIdToFormula();

    return new self(
      $idFormula,
      $idToFormula,
      DrilldownKeysHelper::fromFormula($drilldown),
      $formulaToAnything);
  }

  /**
   * @param \Donquixote\ObCK\Formula\Select\Formula_SelectInterface $idFormula
   * @param \Donquixote\ObCK\IdToFormula\IdToFormulaInterface $idToFormula
   * @param \Donquixote\ObCK\DrilldownKeysHelper\DrilldownKeysHelperInterface $drilldownKeysHelper
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   */
  public function __construct(
    Formula_SelectInterface $idFormula,
    IdToFormulaInterface $idToFormula,
    DrilldownKeysHelperInterface $drilldownKeysHelper,
    FormulaToAnythingInterface $formulaToAnything
  ) {
    $this->idToFormula = $idToFormula;
    $this->formulaToAnything = $formulaToAnything;
    parent::__construct($idFormula, $drilldownKeysHelper);
  }

  /**
   * @inheritDoc
   */
  protected function idIsOptionless(string $id): bool {
    $idFormula = $this->idToFormula->idGetFormula($id);
    if (NULL === $idFormula) {
      return FALSE;
    }
    return $this->formulaIsOptionless($idFormula);
  }

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   *
   * @return bool
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  private function formulaIsOptionless(FormulaInterface $formula): bool {

    $optionlessnessOrNull = $this->formulaToAnything->formula(
      $formula,
      OptionlessnessInterface::class);

    return 1
      && $optionlessnessOrNull instanceof OptionlessnessInterface
      && $optionlessnessOrNull->isOptionless();
  }

  /**
   * @inheritDoc
   */
  protected function idGetSubform(string $id, $subConf): array {

    try {
      if (false === $subFormator = $this->idGetFormatorOrFalse($id)) {
        return [];
      }
    }
    catch (FormulaToAnythingException $e) {
      $subFormator = new FormatorD8_Broken($e->getMessage());
    }

    return $subFormator->confGetD8Form($subConf, NULL);
  }

  /**
   * @param string $id
   *
   * @return \Drupal\cu\Formator\FormatorD8Interface|false
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  private function idGetFormatorOrFalse($id) {
    return $this->formators[$id]
      ?? $this->formators[$id] = $this->idBuildFormatorOrFalse($id);
  }

  /**
   * @param string $id
   *
   * @return \Drupal\cu\Formator\FormatorD8Interface|false
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  private function idBuildFormatorOrFalse($id) {

    if (NULL === $formula = $this->idToFormula->idGetFormula($id)) {
      return FALSE;
    }

    if (NULL === $formator = FormatorD8::fromFormula(
        $formula,
        $this->formulaToAnything
      )
    ) {
      return FALSE;
    }

    return $formator;
  }

}
