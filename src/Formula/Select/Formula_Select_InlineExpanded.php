<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select;

use Donquixote\OCUI\IdToFormula\IdToFormulaInterface;
use Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\OCUI\Formula\DrilldownVal\Formula_DrilldownValInterface;
use Donquixote\OCUI\Formula\Id\Formula_IdInterface;
use Donquixote\OCUI\Text\TextInterface;

class Formula_Select_InlineExpanded implements Formula_SelectInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Select\Formula_SelectInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\IdToFormula\IdToFormulaInterface
   */
  private $idToFormula;

  /**
   * @param \Donquixote\OCUI\Formula\Select\Formula_SelectInterface $decorated
   * @param \Donquixote\OCUI\IdToFormula\IdToFormulaInterface $idToFormula
   */
  public function __construct(
    Formula_SelectInterface $decorated,
    IdToFormulaInterface $idToFormula
  ) {
    $this->decorated = $decorated;
    $this->idToFormula = $idToFormula;
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupedOptions(): array {

    $options = [];
    /** @var string[] $groupOptions */
    foreach ($this->decorated->getGroupedOptions() as $groupLabel => $groupOptions) {
      foreach ($groupOptions as $id => $label) {

        if (NULL === $inlineOptions = $this->idGetInlineOptions($id)) {
          $options[$groupLabel][$id] = $label;
        }
        else {
          foreach ($inlineOptions as $inlineGroupLabel => $inlineGroupOptions) {
            foreach ($inlineGroupOptions as $inlineId => $inlineLabel) {
              $options[$inlineGroupLabel]["$id/$inlineId"] = "$label: $inlineLabel";
            }
          }
          $options[$groupLabel][$id] = "$label - ALL";
        }
      }
    }

    return $options;
  }

  /**
   * @param string $id
   *
   * @return null|string[][]
   */
  private function idGetInlineOptions(string $id): ?array {

    if (NULL === $schema = $this->idGetSelectFormula($id)) {
      return NULL;
    }

    return $schema->getGroupedOptions();
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {

    if (FALSE === /* $pos = */ strpos($id, '/')) {
      return $this->decorated->idGetLabel($id);
    }

    [$prefix, $suffix] = explode('/', $id, 2);

    if (NULL === $subFormula = $this->idGetSelectFormula($prefix)) {
      return NULL;
    }

    return $subFormula->idGetLabel($suffix);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($combinedId): bool {

    if (FALSE === /* $pos = */ strpos($combinedId, '/')) {
      return $this->decorated->idIsKnown($combinedId);
    }

    [$prefix, $suffix] = explode('/', $combinedId, 2);

    if (NULL === $subFormula = $this->idGetSelectFormula($prefix)) {
      return FALSE;
    }

    return $subFormula->idIsKnown($suffix);
  }

  /**
   * @param string|int $id
   *
   * @return \Donquixote\OCUI\Formula\Select\Formula_SelectInterface|null
   */
  private function idGetSelectFormula($id): ?Formula_SelectInterface {

    if (NULL === $idFormula = $this->idGetIdFormula($id)) {
      return NULL;
    }

    if (!$idFormula instanceof Formula_SelectInterface) {
      return NULL;
    }

    return $idFormula;
  }

  /**
* @param string $id
   *
   * @return \Donquixote\OCUI\Formula\Id\Formula_IdInterface|null
   */
  private function idGetIdFormula(string $id): ?Formula_IdInterface {

    if (NULL === $nestedFormula = $this->idToFormula->idGetFormula($id)) {
      return NULL;
    }

    if ($nestedFormula instanceof Formula_DrilldownInterface) {
      return $nestedFormula->getIdFormula();
    }

    if ($nestedFormula instanceof Formula_IdInterface) {
      return $nestedFormula;
    }

    if ($nestedFormula instanceof Formula_DrilldownValInterface) {
      return $nestedFormula->getDecorated()->getIdFormula();
    }

    return NULL;

  }
}
