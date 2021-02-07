<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Boolean;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\DefaultConf\Formula_DefaultConf;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;

class Formula_Boolean_YesNo implements Formula_BooleanInterface {

  /**
   * @param bool $enabledByDefault
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
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
