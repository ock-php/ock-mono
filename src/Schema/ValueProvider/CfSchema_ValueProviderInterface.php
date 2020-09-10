<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\ValueProvider;

use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;

interface CfSchema_ValueProviderInterface extends CfSchema_OptionlessInterface {

  /**
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function getValue();

  /**
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp(): string;

}
