<?php

declare(strict_types=1);

namespace Drupal\ock_preset\Formula;

use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface;
use Drupal\ock_preset\Crud\PresetRepository;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\Drilldown\Formula_Drilldown;
use Ock\Ock\Formula\DrilldownVal\Formula_DrilldownVal;
use Ock\Ock\Formula\DrilldownVal\Formula_DrilldownValInterface;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Formula\Para\Formula_Para;
use Ock\Ock\Formula\Para\Formula_ParaInterface;
use Ock\Ock\IdToFormula\IdToFormulaInterface;
use Ock\Ock\V2V\Drilldown\V2V_Drilldown_FromIdV2V;
use Ock\Ock\V2V\Id\V2V_IdInterface;

class Formula_PresetSelect implements Formula_DrupalSelectInterface, IdToFormulaInterface, V2V_IdInterface {

  /**
   * @param \Drupal\ock_preset\Crud\PresetRepository $repository
   * @param class-string $interface
   */
  public function __construct(
    private readonly PresetRepository $repository,
    private readonly string $interface,
  ) {}

  /**
   * @param string $interface
   *
   * @return \Ock\Ock\Core\Formula\FormulaInterface
   */
  public static function createRealSchema(string $interface): FormulaInterface {
    $repository = PresetRepository::create();
    $formula = new self($repository, $interface);
    return $formula->buildRealSchema();
  }

  /**
   * @return \Ock\Ock\Formula\Para\Formula_ParaInterface
   */
  public function buildRealSchema(): Formula_ParaInterface {
    return new Formula_Para(
      $this->buildConfSchema(),
      Formula::iface($this->interface),
    );
  }

  /**
   * @return \Ock\Ock\Formula\DrilldownVal\Formula_DrilldownValInterface
   */
  public function buildConfSchema(): Formula_DrilldownValInterface {
    return new Formula_DrilldownVal(
      new Formula_Drilldown($this, $this),
      new V2V_Drilldown_FromIdV2V($this),
    );
  }

  /**
   * @param string|int $id
   *
   * @return string|null
   */
  public function idGetLabel(string|int $id): ?string {
    $config = $this->repository->load($this->interface, $id);
    if ($config->isNew()) {
      return NULL;
    }
    return $config->get('label');
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown(string|int $id): bool {
    $config = $this->repository->load($this->interface, $id);
    return !$config->isNew();
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupedOptions(): array {
    $configs = $this->repository->loadForInterface($this->interface);
    $options = [];
    foreach ($configs as $preset_name => $config) {
      $label = $config->get('label');
      if ('' === $label || NULL === $label) {
        $label = $preset_name;
      }
      $options[$preset_name] = $label;
    }

    return ['' => $options];
  }

  /**
   * {@inheritdoc}
   */
  public function idGetFormula(string|int $id): ?FormulaInterface {
    return new Formula_PresetLink($this->interface, $id);
  }

  /**
   * {@inheritdoc}
   */
  public function idGetPhp(string|int $id): string {
    $value = $this->idGetValue($id);
    // This will not be used anyway.
    return var_export($value, TRUE);
  }

  /**
   * @param string|int $id
   *
   * @return mixed
   */
  protected function idGetValue(string|int $id): mixed {
    $config = $this->repository->load($this->interface, $id);
    return $config->get('conf');
  }

}
