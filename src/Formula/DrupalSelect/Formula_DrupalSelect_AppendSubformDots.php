<?php

declare(strict_types=1);

namespace Drupal\cu\Formula\DrupalSelect;

use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;
use Donquixote\ObCK\Incarnator\IncarnatorInterface;
use Donquixote\ObCK\Optionlessness\Optionlessness;

/**
 * Decorator which appends .
 */
class Formula_DrupalSelect_AppendSubformDots implements Formula_DrupalSelectInterface {

  /**
   * @var \Drupal\cu\Formula\DrupalSelect\Formula_DrupalSelectInterface
   */
  private Formula_DrupalSelectInterface $decorated;

  /**
   * @var \Donquixote\ObCK\IdToFormula\IdToFormulaInterface
   */
  private IdToFormulaInterface $idToFormula;

  /**
   * @var \Donquixote\ObCK\Incarnator\IncarnatorInterface
   */
  private IncarnatorInterface $incarnator;

  /**
   * Constructor.
   *
   * @param \Drupal\cu\Formula\DrupalSelect\Formula_DrupalSelectInterface $decorated
   * @param \Donquixote\ObCK\IdToFormula\IdToFormulaInterface $idToFormula
   * @param \Donquixote\ObCK\Incarnator\IncarnatorInterface $incarnator
   */
  public function __construct(
    Formula_DrupalSelectInterface $decorated,
    IdToFormulaInterface $idToFormula,
    IncarnatorInterface $incarnator
  ) {
    $this->decorated = $decorated;
    $this->idToFormula = $idToFormula;
    $this->incarnator = $incarnator;
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupedOptions(): array {
    $grouped_options = $this->decorated->getGroupedOptions();
    foreach ($grouped_options as &$options_in_group) {
      foreach ($options_in_group as $id => &$label) {
        $formula = $this->idToFormula->idGetFormula($id);
        if ($formula === NULL) {
          unset($options_in_group[$id]);
          continue;
        }
        if (!Optionlessness::checkFormula($formula, $this->incarnator)) {
          $label = t('@label…', ['@label' => $label]);
        }
      }
    }
    return $grouped_options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id) {
    return $this->decorated->idGetLabel($id);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {
    if (!$this->decorated->idIsKnown($id)) {
      return FALSE;
    }
    return $this->decorated->idIsKnown($id)
      && $this->idToFormula->idGetFormula($id) !== NULL;
  }

}
