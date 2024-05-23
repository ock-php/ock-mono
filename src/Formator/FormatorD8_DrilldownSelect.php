<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Exception\AdapterException;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\DrilldownKeysHelper\DrilldownKeysHelper;
use Donquixote\Ock\DrilldownKeysHelper\DrilldownKeysHelperInterface;
use Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\Ock\Formula\Select\Formula_SelectInterface;
use Donquixote\Ock\IdToFormula\IdToFormulaInterface;
use Donquixote\Ock\Optionlessness\Optionlessness;

class FormatorD8_DrilldownSelect extends FormatorD8_DrilldownSelectBase {

  /**
   * @var \Drupal\ock\Formator\FormatorD8Interface[]|false[]
   */
  private array $formators = [];

  /**
   * @param \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface $drilldown
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
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
   * @param \Donquixote\Ock\Formula\Select\Formula_SelectInterface $idFormula
   * @param \Donquixote\Ock\IdToFormula\IdToFormulaInterface $idToFormula
   * @param \Donquixote\Ock\DrilldownKeysHelper\DrilldownKeysHelperInterface $drilldownKeysHelper
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
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
      if (false === $subFormator = $this->idGetFormatorOrFalse($id)) {
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
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  private function idGetFormatorOrFalse(string $id): ?FormatorD8Interface {
    return ($this->formators[$id]
      ??= ($this->idBuildFormator($id) ?? FALSE))
      ?: NULL;
  }

  /**
   * @param string $id
   *
   * @return \Drupal\ock\Formator\FormatorD8Interface|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  private function idBuildFormator(string $id): ?FormatorD8Interface {

    if (NULL === $formula = $this->idToFormula->idGetFormula($id)) {
      return NULL;
    }

    return FormatorD8::fromFormula($formula, $this->adapter);
  }

}
