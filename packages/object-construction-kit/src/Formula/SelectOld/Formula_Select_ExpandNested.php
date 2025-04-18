<?php

declare(strict_types=1);

namespace Ock\Ock\Formula\SelectOld;

use Ock\Ock\Formula\Drilldown\Formula_DrilldownInterface;
use Ock\Ock\Formula\DrilldownVal\Formula_DrilldownValInterface;
use Ock\Ock\Formula\Id\Formula_IdInterface;
use Ock\Ock\IdToFormula\IdToFormulaInterface;
use Ock\Ock\Text\Text;
use Ock\Ock\Text\TextInterface;

class Formula_Select_ExpandNested extends Formula_Select_BufferedBase {

  /**
   * Constructor.
   *
   * @param \Ock\Ock\Formula\SelectOld\Formula_SelectInterface $decorated
   * @param \Ock\Ock\IdToFormula\IdToFormulaInterface<\Ock\Ock\Core\Formula\FormulaInterface> $idToFormula
   *
   * @phpstan-ignore parameter.deprecatedInterface
   */
  public function __construct(
    private readonly Formula_SelectInterface $decorated,
    private readonly IdToFormulaInterface $idToFormula
  ) {}

  /**
   * {@inheritdoc}
   */
  protected function initialize(array &$grouped_options, array &$group_labels): void {
    foreach ($this->decorated->getOptGroups() as $group_id => $group_label) {
      foreach ($this->decorated->getOptions($group_id) as $id => $label) {
        $inline_formula = $this->idGetSelectFormula($id);
        if ($inline_formula === NULL) {
          $grouped_options[$group_id][$id] = $label;
          $group_labels[$group_id] = $group_label;
        }
        else {
          foreach ($inline_formula->getOptGroups() as $inline_group_id => $_) {
            foreach ($inline_formula->getOptions($inline_group_id) as $inline_id => $inline_label) {
              $grouped_options[$inline_group_id]["$id/$inline_id"] = Text::builder()
                ->replace('@label', $label)
                ->replace('@inline_label', $inline_label)
                ->s('@label: @inline_label');
            }
          }
          $grouped_options[$group_id][$id] = Text::fluent($label)
            ->wrapT('@label', '@label - ALL');
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(string|int $id): ?TextInterface {

    if (!is_string($id) || !str_contains($id, '/')) {
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
  public function idIsKnown(string|int $id): bool {

    if (!str_contains((string) $id, '/')) {
      return $this->decorated->idIsKnown($id);
    }

    [$prefix, $suffix] = explode('/', (string) $id, 2);

    if (NULL === $subFormula = $this->idGetSelectFormula($prefix)) {
      return FALSE;
    }

    return $subFormula->idIsKnown($suffix);
  }

  /**
   * @param int|string $id
   *
   * @return \Ock\Ock\Formula\SelectOld\Formula_SelectInterface|null
   * @throws \Ock\Ock\Exception\FormulaException
   *
   * @phpstan-ignore return.deprecatedInterface
   */
  private function idGetSelectFormula(int|string $id): ?Formula_SelectInterface {

    if (NULL === $idFormula = $this->idGetIdFormula($id)) {
      return NULL;
    }

    if (!$idFormula instanceof Formula_SelectInterface) {
      return NULL;
    }

    return $idFormula;
  }

  /**
   * @param string|int $id
   *
   * @return \Ock\Ock\Formula\Id\Formula_IdInterface|null
   * @throws \Ock\Ock\Exception\FormulaException
   */
  private function idGetIdFormula(string|int $id): ?Formula_IdInterface {

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
