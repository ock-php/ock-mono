<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\ValueProvider;

use Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface;

interface Formula_ValueProviderInterface extends Formula_OptionlessInterface {

  /**
   * @return mixed
   *
   * @throws \Donquixote\Ock\Exception\EvaluatorException
   */
  public function getValue();

  /**
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp(): string;

}
