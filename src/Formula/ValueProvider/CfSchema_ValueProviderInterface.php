<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\ValueProvider;

use Donquixote\OCUI\Formula\Optionless\CfSchema_OptionlessInterface;

interface CfSchema_ValueProviderInterface extends CfSchema_OptionlessInterface {

  /**
   * @return mixed
   *
   * @throws \Donquixote\OCUI\Exception\EvaluatorException
   */
  public function getValue();

  /**
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp(): string;

}
