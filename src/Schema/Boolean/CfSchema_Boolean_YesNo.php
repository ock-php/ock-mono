<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Boolean;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\DefaultConf\CfSchema_DefaultConf;
use Donquixote\Cf\Text\Text;
use Donquixote\Cf\Text\TextInterface;

class CfSchema_Boolean_YesNo implements CfSchema_BooleanInterface {

  /**
   * @param bool $enabledByDefault
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
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
