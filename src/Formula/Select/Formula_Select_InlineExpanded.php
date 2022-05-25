<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Select;

use Donquixote\Adaptism\Exception\AdapterException;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Formula\Id\Formula_IdInterface;
use Donquixote\Ock\IdToFormula\IdToFormulaInterface;
use Donquixote\Ock\InlineDrilldown\InlineDrilldownInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;

class Formula_Select_InlineExpanded extends Formula_Select_BufferedBase {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\Select\Formula_SelectInterface $decorated
   * @param \Donquixote\Ock\IdToFormula\IdToFormulaInterface $idToFormula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   */
  public function __construct(
    private readonly Formula_SelectInterface $decorated,
    private readonly IdToFormulaInterface $idToFormula,
    private readonly UniversalAdapterInterface $universalAdapter,
  ) {}

  /**
   * {@inheritdoc}
   */
  protected function initialize(array &$grouped_options, array &$group_labels): void {
    foreach (['' => NULL] + $this->decorated->getOptGroups() as $group_id => $group_label) {
      foreach ($this->decorated->getOptions($group_id) as $id => $label) {
        $inline_formula = $this->idGetSelectFormula($id);
        if ($inline_formula === NULL) {
          $grouped_options[$group_id][$id] = $label;
          if ($group_label !== NULL) {
            $group_labels[$group_id] = $group_label;
          }
        }
        else {
          foreach (['' => NULL] + $inline_formula->getOptGroups() as $inline_group_id => $inline_group_label) {
            foreach ($inline_formula->getOptions($inline_group_id) as $inline_id => $inline_label) {
              $grouped_options[$inline_group_id]["$id/$inline_id"] = Text::label(
                $label,
                $inline_label);
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

    if (!str_contains((string) $id, '/')) {
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

    if (!\str_contains((string) $id, '/')) {
      return $this->decorated->idIsKnown($id);
    }

    [$prefix, $suffix] = explode('/', (string) $id, 2);

    if (NULL === $subFormula = $this->idGetSelectFormula($prefix)) {
      return FALSE;
    }

    return $subFormula->idIsKnown($suffix);
  }

  /**
   * @param string|int $id
   *
   * @return \Donquixote\Ock\Formula\Select\Formula_SelectInterface|null
   */
  private function idGetSelectFormula(string|int $id): ?Formula_SelectInterface {

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
   * @return \Donquixote\Ock\Formula\Id\Formula_IdInterface|null
   */
  private function idGetIdFormula(string|int $id): ?Formula_IdInterface {
    if (NULL === $nestedFormula = $this->idToFormula->idGetFormula($id)) {
      // This id has no children.
      return NULL;
    }

    try {
      return $this->universalAdapter->adapt(
        $nestedFormula,
        InlineDrilldownInterface::class,
      )?->getIdFormula();
    }
    catch (AdapterException) {
      // @todo Log this.
      return NULL;
    }
  }

}
