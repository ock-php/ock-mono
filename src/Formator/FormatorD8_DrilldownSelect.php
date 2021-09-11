<?php
declare(strict_types=1);

namespace Drupal\cu\Formator;

use Donquixote\ObCK\DrilldownKeysHelper\DrilldownKeysHelper;
use Donquixote\ObCK\DrilldownKeysHelper\DrilldownKeysHelperInterface;
use Donquixote\ObCK\Exception\IncarnatorException;
use Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\ObCK\Formula\Select\Formula_SelectInterface;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;
use Donquixote\ObCK\Incarnator\IncarnatorInterface;
use Donquixote\ObCK\Optionlessness\Optionlessness;

class FormatorD8_DrilldownSelect extends FormatorD8_DrilldownSelectBase {

  /**
   * @var \Donquixote\ObCK\IdToFormula\IdToFormulaInterface
   */
  private $idToFormula;

  /**
   * @var \Donquixote\ObCK\Incarnator\IncarnatorInterface
   */
  private $incarnator;

  /**
   * @var \Drupal\cu\Formator\FormatorD8Interface[]|false[]
   */
  private $formators = [];

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface $drilldown
   * @param \Donquixote\ObCK\Incarnator\IncarnatorInterface $incarnator
   *
   * @return self|null
   */
  public static function create(Formula_DrilldownInterface $drilldown, IncarnatorInterface $incarnator): ?self {

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
      $incarnator);
  }

  /**
   * @param \Donquixote\ObCK\Formula\Select\Formula_SelectInterface $idFormula
   * @param \Donquixote\ObCK\IdToFormula\IdToFormulaInterface $idToFormula
   * @param \Donquixote\ObCK\DrilldownKeysHelper\DrilldownKeysHelperInterface $drilldownKeysHelper
   * @param \Donquixote\ObCK\Incarnator\IncarnatorInterface $incarnator
   */
  public function __construct(
    Formula_SelectInterface $idFormula,
    IdToFormulaInterface $idToFormula,
    DrilldownKeysHelperInterface $drilldownKeysHelper,
    IncarnatorInterface $incarnator
  ) {
    $this->idToFormula = $idToFormula;
    $this->incarnator = $incarnator;
    parent::__construct($idFormula, $drilldownKeysHelper);
  }

  /**
   * @inheritDoc
   */
  protected function idIsOptionless(string $id): bool {
    return ($formula = $this->idToFormula->idGetFormula($id))
      && Optionlessness::checkFormula($formula, $this->incarnator);
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
    catch (IncarnatorException $e) {
      $subFormator = new FormatorD8_Broken($e->getMessage());
    }

    return $subFormator->confGetD8Form($subConf, NULL);
  }

  /**
   * @param string $id
   *
   * @return \Drupal\cu\Formator\FormatorD8Interface|null
   *
   * @throws \Donquixote\ObCK\Exception\IncarnatorException
   */
  private function idGetFormatorOrFalse($id): ?FormatorD8Interface {
    return ($this->formators[$id]
      ??= ($this->idBuildFormator($id) ?? FALSE))
      ?: NULL;
  }

  /**
   * @param string $id
   *
   * @return \Drupal\cu\Formator\FormatorD8Interface|null
   *
   * @throws \Donquixote\ObCK\Exception\IncarnatorException
   *   Unsupported formula.
   */
  private function idBuildFormator($id): ?FormatorD8Interface {

    if (NULL === $formula = $this->idToFormula->idGetFormula($id)) {
      return NULL;
    }

    return FormatorD8::fromFormula(
        $formula,
        $this->incarnator);
  }

}
