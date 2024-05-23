<?php

declare(strict_types=1);

namespace Drupal\ock\Formula\DrupalSelect;

use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\Ock\IdToFormula\IdToFormulaInterface;
use Ock\Ock\Optionlessness\Optionlessness;
use Drupal\Component\Render\MarkupInterface;

/**
 * Decorator which appends .
 */
class Formula_DrupalSelect_AppendSubformDots implements Formula_DrupalSelectInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface $decorated
   * @param \Ock\Ock\IdToFormula\IdToFormulaInterface $idToFormula
   * @param \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface $adapter
   */
  public function __construct(
    private readonly Formula_DrupalSelectInterface $decorated,
    private readonly IdToFormulaInterface $idToFormula,
    private readonly UniversalAdapterInterface $adapter,
  ) {}

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
        if (!Optionlessness::checkFormula($formula, $this->adapter)) {
          $label = t('@label…', ['@label' => $label]);
        }
      }
    }
    return $grouped_options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(string|int $id): string|MarkupInterface|null {
    return $this->decorated->idGetLabel($id);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(string|int $id): bool {
    if (!$this->decorated->idIsKnown($id)) {
      return FALSE;
    }
    return $this->decorated->idIsKnown($id)
      && $this->idToFormula->idGetFormula($id) !== NULL;
  }

}
