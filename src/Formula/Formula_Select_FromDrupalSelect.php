<?php
declare(strict_types=1);

namespace Drupal\cu\Formula;

use Donquixote\ObCK\Formula\Select\Formula_Select_BufferedBase;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;
use Drupal\cu\DrupalText;
use Drupal\cu\Formula\DrupalSelect\Formula_DrupalSelectInterface;

/**
 * Adapter for Drupal select formulas.
 *
 * @see \Drupal\cu\Formula\DrupalSelect\Formula_DrupalSelect_FromCommonSelect
 */
class Formula_Select_FromDrupalSelect extends Formula_Select_BufferedBase {

  /**
   * @var \Drupal\cu\Formula\DrupalSelect\Formula_DrupalSelectInterface
   */
  private Formula_DrupalSelectInterface $formula;

  /**
   * Constructor.
   *
   * @param \Drupal\cu\Formula\DrupalSelect\Formula_DrupalSelectInterface $formula
   */
  public function __construct(Formula_DrupalSelectInterface $formula) {
    $this->formula = $formula;
  }

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
  public function idIsKnown($id): bool {
    return $this->formula->idIsKnown($id);
  }

  /**
   * {@inheritdoc}
   */
  protected function initialize(array &$grouped_options, array &$group_labels): void {
    foreach ($this->formula->getGroupedOptions() as $group_label => $labels_in_group) {
      $grouped_options[$group_label] = DrupalText::multiple($labels_in_group);
      $group_labels[$group_label] = Text::s($group_label);
    }
  }

}
