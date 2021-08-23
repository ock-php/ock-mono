<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\DrilldownKeysHelper\DrilldownKeysHelper;
use Donquixote\OCUI\DrilldownKeysHelper\DrilldownKeysHelperInterface;
use Donquixote\OCUI\Exception\FormulaToAnythingException;
use Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\OCUI\Formula\Select\Formula_SelectInterface;
use Donquixote\OCUI\IdToFormula\IdToFormulaInterface;
use Donquixote\OCUI\Optionlessness\OptionlessnessInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Drupal\Core\Form\FormStateInterface;

class FormatorD8_DrilldownSelect extends FormatorD8_DrilldownSelectBase {

  /**
   * @var \Donquixote\OCUI\IdToFormula\IdToFormulaInterface
   */
  private $idToFormula;

  /**
   * @var \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface
   */
  private $formulaToAnything;

  /**
   * @var \Drupal\cu\Formator\FormatorD8Interface[]|false[]
   */
  private $formators = [];

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface $drilldown
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
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
   * @param \Donquixote\OCUI\Formula\Select\Formula_SelectInterface $idFormula
   * @param \Donquixote\OCUI\IdToFormula\IdToFormulaInterface $idToFormula
   * @param \Donquixote\OCUI\DrilldownKeysHelper\DrilldownKeysHelperInterface $drilldownKeysHelper
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
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
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   *
   * @return bool
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
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
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
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
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
