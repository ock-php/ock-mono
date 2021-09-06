<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Select;

use Donquixote\ObCK\Exception\FormulaToAnythingException;
use Donquixote\ObCK\Formula\Id\Formula_IdInterface;
use Donquixote\ObCK\Nursery\NurseryInterface;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;
use Donquixote\ObCK\InlineDrilldown\InlineDrilldown;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;

class Formula_Select_InlineExpanded extends Formula_Select_BufferedBase {

  /**
   * @var \Donquixote\ObCK\Formula\Select\Formula_SelectInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\ObCK\IdToFormula\IdToFormulaInterface
   */
  private $idToFormula;

  /**
   * @var \Donquixote\ObCK\Nursery\NurseryInterface
   */
  private NurseryInterface $helper;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Formula\Select\Formula_SelectInterface $decorated
   * @param \Donquixote\ObCK\IdToFormula\IdToFormulaInterface $idToFormula
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $helper
   */
  public function __construct(
    Formula_SelectInterface $decorated,
    IdToFormulaInterface $idToFormula,
    NurseryInterface $helper
  ) {
    $this->decorated = $decorated;
    $this->idToFormula = $idToFormula;
    $this->helper = $helper;
  }

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
              $grouped_options[$inline_group_id]["$id/$inline_id"] = Text::s('@label: @inline_label', [
                '@label' => $label,
                '@inline_label' => $inline_label,
              ]);
            }
          }
          $grouped_options[$group_id][$id] = Text::t('@label - ALL', [
            '@label' => $label,
          ]);
        }
      }
    }
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
  public function idIsKnown($id): bool {

    if (FALSE === /* $pos = */ strpos($id, '/')) {
      return $this->decorated->idIsKnown($id);
    }

    [$prefix, $suffix] = explode('/', $id, 2);

    if (NULL === $subFormula = $this->idGetSelectFormula($prefix)) {
      return FALSE;
    }

    return $subFormula->idIsKnown($suffix);
  }

  /**
   * @param string|int $id
   *
   * @return \Donquixote\ObCK\Formula\Select\Formula_SelectInterface|null
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
   * @return \Donquixote\ObCK\Formula\Id\Formula_IdInterface|null
   */
  private function idGetIdFormula(string $id): ?Formula_IdInterface {

    if (NULL === $nestedFormula = $this->idToFormula->idGetFormula($id)) {
      return NULL;
    }

    try {
      $subtree = InlineDrilldown::fromFormula($nestedFormula, $this->helper);
    }
    catch (FormulaToAnythingException $e) {
      return NULL;
    }

    return $subtree->getIdFormula();
  }

}
