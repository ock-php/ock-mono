<?php
declare(strict_types=1);

namespace Drupal\ock_preset\Schema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Exception\EvaluatorException;
use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Donquixote\Cf\Schema\CfSchema;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownVal;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownValInterface;
use Donquixote\Cf\Schema\Para\CfSchema_Para;
use Donquixote\Cf\Schema\Select\CfSchema_SelectInterface;
use Donquixote\Cf\Util\PhpUtil;
use Donquixote\Cf\V2V\Drilldown\V2V_Drilldown_FromIdV2V;
use Donquixote\Cf\V2V\Id\V2V_IdInterface;
use Drupal\ock_preset\Crud\PresetRepository;

class CfSchema_PresetSelect implements CfSchema_SelectInterface, IdToSchemaInterface, V2V_IdInterface {

  /**
   * @var \Drupal\ock_preset\Crud\PresetRepository
   */
  private $repository;

  /**
   * @var string
   */
  private $interface;

  /**
   * @param string $interface
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createRealSchema($interface): CfSchemaInterface {
    $repository = PresetRepository::create();
    $schema = new self($repository, $interface);
    return $schema->buildRealSchema();
  }

  /**
   * @param \Drupal\ock_preset\Crud\PresetRepository $repository
   * @param string $interface
   */
  public function __construct(PresetRepository $repository, $interface) {
    $this->repository = $repository;
    $this->interface = $interface;
  }

  /**
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public function buildRealSchema(): CfSchemaInterface|CfSchema_Para {

    return new CfSchema_Para(
      $this->buildConfSchema(),
      CfSchema::iface($this->interface));
  }

  /**
   * @return \Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownValInterface
   */
  public function buildConfSchema(): CfSchema_DrilldownValInterface {

    return new CfSchema_DrilldownVal(
      new CfSchema_Drilldown(
        $this,
        $this),
      new V2V_Drilldown_FromIdV2V($this));
  }

  /**
   * @param string|int $id
   *
   * @return string|null
   */
  public function idGetLabel($id): ?string {

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
  public function idIsKnown($id): bool {

    $config = $this->repository->load($this->interface, $id);

    return !$config->isNew();
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
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
   * @param string $id
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($id): ?CfSchemaInterface {
    return new CfSchema_PresetLink($this->interface, $id);
  }

  /**
   * @param string|int $id
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function idGetValue($id): mixed {

    $config = $this->repository->load($this->interface, $id);

    return $config->get('conf');
  }

  /**
   * @param string|int $id
   *
   * @return string
   */
  public function idGetPhp($id): string {

    try {
      $value = $this->idGetValue($id);
    }
    catch (EvaluatorException $e) {
      return PhpUtil::exception(
        EvaluatorException::class,
        $e->getMessage());
    }

    // This will not be used anyway.
    return var_export($value, TRUE);
  }

}
