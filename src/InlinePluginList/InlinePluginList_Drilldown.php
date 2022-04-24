<?php

declare(strict_types=1);

namespace Donquixote\Ock\InlinePluginList;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\Ock\Formula\DrilldownVal\Formula_DrilldownValInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Select\Formula_SelectInterface;
use Donquixote\Ock\Formula\ValueToValue\Formula_ValueToValue;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Plugin\Plugin;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\V2V\Drilldown\V2V_Drilldown_Trivial;
use Donquixote\Ock\V2V\Drilldown\V2V_DrilldownInterface;
use Donquixote\Ock\V2V\Value\V2V_Value_DrilldownFixedId;

class InlinePluginList_Drilldown implements InlinePluginListInterface {

  /**
   * @var \Donquixote\Ock\Formula\Select\Formula_SelectInterface
   */
  private Formula_SelectInterface $idFormula;

  /**
   * @var \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface
   */
  private Formula_DrilldownInterface $formula;

  /**
   * @var \Donquixote\Ock\V2V\Drilldown\V2V_DrilldownInterface
   */
  private V2V_DrilldownInterface $v2v;

  /**
   * @param \Donquixote\Ock\Formula\DrilldownVal\Formula_DrilldownValInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return self
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function fromDrilldownVal(
    Formula_DrilldownValInterface $formula,
    UniversalAdapterInterface $universalAdapter
  ): ?self {
    return self::create(
      $formula->getDecorated(),
      $formula->getV2V(),
      $universalAdapter);
  }

  /**
   * @param \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return self
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function fromDrilldown(
    Formula_DrilldownInterface $formula,
    UniversalAdapterInterface $universalAdapter
  ): ?self {
    return self::create(
      $formula,
      new V2V_Drilldown_Trivial(),
      $universalAdapter);
  }

  /**
   * @param \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface $formula
   * @param \Donquixote\Ock\V2V\Drilldown\V2V_DrilldownInterface $v2v
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return static|null
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public static function create(
    Formula_DrilldownInterface $formula,
    V2V_DrilldownInterface $v2v,
    UniversalAdapterInterface $universalAdapter
  ): ?self {
    $idFormula = Formula::replace(
      $formula->getIdFormula(),
      $universalAdapter);
    if (!$idFormula instanceof Formula_SelectInterface) {
      return NULL;
    }
    return new self(
      $idFormula,
      $formula,
      $v2v);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\Select\Formula_SelectInterface $idFormula
   * @param \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface $formula
   * @param \Donquixote\Ock\V2V\Drilldown\V2V_DrilldownInterface $v2v
   */
  public function __construct(
    Formula_SelectInterface $idFormula,
    Formula_DrilldownInterface $formula,
    V2V_DrilldownInterface $v2v
  ) {
    $this->idFormula = $idFormula;
    $this->formula = $formula;
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds(): array {
    $options = $this->idFormula->getOptions(NULL);
    foreach ($this->idFormula->getOptGroups() as $group_id => $groupLabel) {
      $options += $this->idFormula->getOptions($group_id);
    }
    return array_keys($options);
  }

  /**
   * {@inheritdoc}
   */
  public function idGetPlugin(string $id): ?Plugin {
    $formula = $this->formula->getIdToFormula()->idGetFormula($id);
    if ($formula === NULL) {
      return NULL;
    }
    if ($this->v2v) {
      $formula = new Formula_ValueToValue(
        $formula,
        new V2V_Value_DrilldownFixedId($this->v2v, $id));
    }
    return new Plugin(
      $this->idFormula->idGetLabel($id) ?? Text::s($id),
      NULL,
      $formula,
      []);
  }

}
