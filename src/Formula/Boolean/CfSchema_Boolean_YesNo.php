<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Boolean;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\Formula\DefaultConf\CfSchema_DefaultConf;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;

class CfSchema_Boolean_YesNo implements CfSchema_BooleanInterface {

  /**
   * @param bool $enabledByDefault
   *
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  public static function create($enabledByDefault = FALSE): CfSchemaInterface {

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
