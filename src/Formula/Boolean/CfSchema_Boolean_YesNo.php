<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Boolean;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\DefaultConf\CfSchema_DefaultConf;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;

class CfSchema_Boolean_YesNo implements Formula_BooleanInterface {

  /**
   * @param bool $enabledByDefault
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public static function create($enabledByDefault = FALSE): FormulaInterface {

    if ($enabledByDefault) {
      return new CfSchema_DefaultConf(new self, TRUE);
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
