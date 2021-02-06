<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\TypeToSchema;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Schema\CfSchemaInterface;

interface TypeToSchemaInterface {

  /**
   * @param string $type
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  public function typeGetSchema(string $type, CfContextInterface $context = NULL): CfSchemaInterface;

}
