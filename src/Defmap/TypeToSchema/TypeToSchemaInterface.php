<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\TypeToSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;

interface TypeToSchemaInterface {

  /**
   * @param string $type
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public function typeGetSchema(string $type, CfContextInterface $context = NULL): CfSchemaInterface;

}
