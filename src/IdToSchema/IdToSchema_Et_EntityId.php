<?php
declare(strict_types=1);

namespace Drupal\renderkit\IdToSchema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Drupal\renderkit\Schema\CfSchema_EntityId;

class IdToSchema_Et_EntityId implements IdToSchemaInterface {

  /**
   * @param string $entityTypeId
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($entityTypeId): ?CfSchemaInterface {
    return new CfSchema_EntityId($entityTypeId);
  }
}
