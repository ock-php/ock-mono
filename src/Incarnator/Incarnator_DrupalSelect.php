<?php

declare(strict_types=1);

namespace Drupal\ock\Incarnator;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Incarnator\Incarnator_FormulaReplacerBase;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Drupal\ock\Formula\DrupalSelect\Formula_DrupalSelectInterface;
use Drupal\ock\Formula\Formula_Select_FromDrupalSelect;

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
