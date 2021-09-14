<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Select;

use Donquixote\Ock\Text\TextInterface;

class Formula_Select_MergeMultiple extends Formula_Select_BufferedBase {

  /**
   * @var \Donquixote\Ock\Formula\Select\Formula_SelectInterface[]
   */
  private array $formulas;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\Select\Formula_SelectInterface[] $formulas
   */
  public function __construct(array $formulas) {
    $this->formulas = $formulas;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {
    foreach ($this->formulas as $formula) {
      $label = $formula->idGetLabel($id);
      if ($label !== NULL) {
        return $label;
      }
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {
    foreach ($this->formulas as $formula) {
      if ($formula->idIsKnown($id)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  protected function initialize(array &$grouped_options, array &$group_labels): void {
    foreach ($this->formulas as $formula) {
      foreach ($formula->getOptGroups() as $group_id => $group_label) {
        $group_labels[$group_id] = $group_label;
        foreach ($formula->getOptions($group_id) as $id => $label) {
          $grouped_options[$group_id][$id] = $label;
        }
      }
    }
  }

}
