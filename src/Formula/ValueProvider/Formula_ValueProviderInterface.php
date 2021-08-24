<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\ValueProvider;

use Donquixote\ObCK\Formula\Optionless\Formula_OptionlessInterface;

interface Formula_ValueProviderInterface extends Formula_OptionlessInterface {

  /**
   * @return mixed
   *
   * @throws \Donquixote\ObCK\Exception\EvaluatorException
   */
  public function getValue();

  /**
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp(): string;

}
