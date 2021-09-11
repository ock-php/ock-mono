<?php

declare(strict_types=1);

namespace Drupal\cu\Incarnator;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Incarnator\Incarnator_FormulaReplacerBase;
use Donquixote\ObCK\Incarnator\IncarnatorInterface;
use Drupal\cu\Formula\DrupalSelect\Formula_DrupalSelectInterface;
use Drupal\cu\Formula\Formula_Select_FromDrupalSelect;

/**
 * @STA
 */
class Incarnator_DrupalSelect extends Incarnator_FormulaReplacerBase {

  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct(Formula_DrupalSelectInterface::class);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaGetReplacement(
    FormulaInterface $formula,
    IncarnatorInterface $incarnator
  ): ?FormulaInterface {
    /** @var Formula_DrupalSelectInterface $formula */
    return new Formula_Select_FromDrupalSelect($formula);
  }

}
