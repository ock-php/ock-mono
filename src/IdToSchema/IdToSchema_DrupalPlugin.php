<?php
declare(strict_types=1);

namespace Drupal\renderkit8\IdToSchema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\IdToSchema\IdToSchemaInterface;

class IdToSchema_DrupalPlugin implements IdToSchemaInterface {

  public function __construct() {
  }

  /**
   * @inheritDoc
   */
  public function idGetSchema($id): ?CfSchemaInterface {
    // TODO: Implement idGetSchema() method.
  }

}
