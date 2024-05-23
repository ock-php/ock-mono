<?php
declare(strict_types=1);

namespace Drupal\ock\Formator;

use Ock\Adaptism\Exception\AdapterException;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\Ock\DrilldownKeysHelper\DrilldownKeysHelper;
use Ock\Ock\DrilldownKeysHelper\DrilldownKeysHelperInterface;
use Ock\Ock\Formula\Drilldown\Formula_DrilldownInterface;
use Ock\Ock\Formula\Select\Formula_SelectInterface;
use Ock\Ock\IdToFormula\IdToFormulaInterface;
use Ock\Ock\Optionlessness\Optionlessness;
use Drupal\Component\Render\MarkupInterface;

class FormatorD8_Drilldown implements FormatorD8Interface {

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
  // #[Adapter]
  public static function create(Formula_DrilldownInterface $drilldown, UniversalAdapterInterface $adapter): ?self {

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
      $adapter);
  }

  /**
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
  ) {}

  protected function idIsOptionless(string $id): bool {
    return ($formula = $this->idToFormula->idGetFormula($id))
      &&  Optionlessness::checkFormula($formula, $this->adapter);
  }

  /**
   * @param string $id
   * @param $subConf
   *
   * @return array
   */
  protected function idGetSubform(string $id, $subConf): array {

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
   * @return \Drupal\ock\Formator\FormatorD8Interface|false
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
   */
  private function idGetFormatorOrFalse(string $id): false|FormatorD8Interface {
    return $this->formators[$id]
      ??= $this->idBuildFormatorOrFalse($id);
  }

  /**
   * @param string $id
   *
   * @return \Drupal\ock\Formator\FormatorD8Interface|false
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
   */
  private function idBuildFormatorOrFalse(string $id): false|FormatorD8Interface {

    if (NULL === $formula = $this->idToFormula->idGetFormula($id)) {
      return FALSE;
    }

    if (NULL === $formator = FormatorD8::fromFormula(
        $formula,
        $this->adapter
      )
    ) {
      return FALSE;
    }

    return $formator;
  }

  public function confGetD8Form(mixed $conf, MarkupInterface|string|null $label): array {
    // @todo Implement this?
    return [
      '#markup' => __METHOD__ . '() is not implemented.',
    ];
  }

}
