<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\TwoStep;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;

interface CfSchema_TwoStepInterface extends CfSchemaInterface {

  /**
   * @return string
   */
  public function getFirstStepKey(): string;

  /**
   * @return string
   */
  public function getSecondStepKey(): string;

  /**
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  public function getFirstStepSchema(): CfSchemaInterface;

  /**
   * @param mixed $firstStepValue
   *   Value from the first step of configuration.
   *
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface|null
   *
   * @todo return NULL or throw exception?
   */
  public function firstStepValueGetSecondStepSchema($firstStepValue): ?CfSchemaInterface;

}
