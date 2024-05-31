<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Ock\Adaptism\Attribute\Adapter;
use Ock\Adaptism\Exception\AdapterException;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\Ock\DrilldownKeysHelper\DrilldownKeysHelper;
use Ock\Ock\DrilldownKeysHelper\DrilldownKeysHelperInterface;
use Ock\Ock\Formula\Drilldown\Formula_DrilldownInterface;
use Ock\Ock\Formula\Select\Formula_SelectInterface;
use Ock\Ock\IdToFormula\IdToFormulaInterface;
use Ock\Ock\Optionlessness\Optionlessness;

class FormatorD8_DrilldownSelect extends FormatorD8_DrilldownSelectBase {

  /**
   * @var \Drupal\ock\Formator\FormatorD8Interface[]|false[]
   */
  private array $formators = [];

  /**
   * @param \Ock\Ock\Formula\Drilldown\Formula_DrilldownInterface $drilldown
   * @param \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   *
   * @return self|null
   */
  #[Adapter]
  public static function create(Formula_DrilldownInterface $drilldown, UniversalAdapterInterface $adapter): ?self {

    $idFormula = $drilldown->getIdFormula();

    if (!$idFormula instanceof Formula_SelectInterface) {
      // Not supported. Write your own formator.
      return NULL;
    }

    $idToFormula = $drilldown->getIdToFormula();

    $instance = new self(
      $idFormula,
      $idToFormula,
      DrilldownKeysHelper::fromFormula($drilldown),
      $adapter,
    );

    if ($drilldown->allowsNull()) {
      $instance = $instance->getOptionalFormator();
    }

    return $instance;
  }

  /**
   * Constructor.
   *
   * @param \Ock\Ock\Formula\Select\Formula_SelectInterface $idFormula
   * @param \Ock\Ock\IdToFormula\IdToFormulaInterface $idToFormula
   * @param \Ock\Ock\DrilldownKeysHelper\DrilldownKeysHelperInterface $drilldownKeysHelper
   * @param \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   */
  public function __construct(
    Formula_SelectInterface $idFormula,
    private readonly IdToFormulaInterface $idToFormula,
    DrilldownKeysHelperInterface $drilldownKeysHelper,
    private readonly UniversalAdapterInterface $adapter,
  ) {
    parent::__construct($idFormula, $drilldownKeysHelper);
  }

  /**
   * @inheritDoc
   */
  protected function idIsOptionless(string $id): bool {
    return ($formula = $this->idToFormula->idGetFormula($id))
      && Optionlessness::checkFormula($formula, $this->adapter);
  }

  /**
   * @inheritDoc
   */
  protected function idGetSubform(string $id, mixed $subConf): array {

    try {
      if (null === $subFormator = $this->idGetFormator($id)) {
        return [];
      }
    }
    catch (AdapterException $e) {
      $subFormator = new FormatorD8_Broken($e->getMessage());
    }

    return $subFormator->confGetD8Form($subConf, NULL);
  }

  /**
   * @param string $id
   *
   * @return \Drupal\ock\Formator\FormatorD8Interface|null
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
   */
  private function idGetFormator(string $id): ?FormatorD8Interface {
    return ($this->formators[$id]
      ??= ($this->idBuildFormator($id) ?? FALSE))
      ?: NULL;
  }

  /**
   * @param string $id
   *
   * @return \Drupal\ock\Formator\FormatorD8Interface|null
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
   */
  private function idBuildFormator(string $id): ?FormatorD8Interface {

    if (NULL === $formula = $this->idToFormula->idGetFormula($id)) {
      return NULL;
    }

    return FormatorD8::fromFormula($formula, $this->adapter);
  }

}
