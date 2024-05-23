<?php
declare(strict_types=1);

namespace Drupal\ock\Formula;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Ock\Formula\Select\Formula_Select_BufferedBase;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;
use Drupal\ock\DrupalText;
use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface;

/**
 * Adapter for Drupal select formulas.
 *
 * @see \Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelect_FromCommonSelect
 */
#[Adapter]
class Formula_Select_FromDrupalSelect extends Formula_Select_BufferedBase {

  /**
   * Constructor.
   *
   * @param \Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface $formula
   */
  public function __construct(
    #[Adaptee]
    private readonly Formula_DrupalSelectInterface $formula,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {
    $label = $this->formula->idGetLabel($id);
    if ($label === NULL) {
      return NULL;
    }
    return DrupalText::fromVarOr($label, $id);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(string|int $id): bool {
    return $this->formula->idIsKnown($id);
  }

  /**
   * {@inheritdoc}
   */
  protected function initialize(array &$map, array &$labels, array &$groupLabels): void {
    foreach ($this->formula->getGroupedOptions() as $groupLabel => $labelsInGroup) {
      $groupLabels[$groupLabel] = Text::s($groupLabel);
      $labels += DrupalText::multiple($labelsInGroup);
      $map += array_fill_keys(array_keys($labelsInGroup), $groupLabel);
    }
  }

}
