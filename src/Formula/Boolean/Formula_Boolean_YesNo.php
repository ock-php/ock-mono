<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Boolean;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\DefaultConf\Formula_DefaultConf;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;

class Formula_Boolean_YesNo implements Formula_BooleanInterface {

  /**
   * @param bool $enabledByDefault
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function create($enabledByDefault = FALSE): FormulaInterface {

    if ($enabledByDefault) {
      return new Formula_DefaultConf(new self, TRUE);
    }

    return new self();
  }

  /**
   * {@inheritdoc}
   */
  public function getTrueSummary(): ?TextInterface {
    return Text::t('Yes');
  }

  /**
   * {@inheritdoc}
   */
  public function getFalseSummary(): ?TextInterface {
    return Text::t('No');
  }
}
