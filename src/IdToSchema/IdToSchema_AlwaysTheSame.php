<?php
declare(strict_types=1);

namespace Donquixote\Cf\IdToSchema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

class IdToSchema_AlwaysTheSame implements IdToSchemaInterface {

  /**
   * @var \Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProvider_FixedValue
   */
  private $sameSchema;

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $sameSchema
   */
  public function __construct(CfSchemaInterface $sameSchema) {
    $this->sameSchema = $sameSchema;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetSchema($id): ?CfSchemaInterface {
    return $this->sameSchema;
  }
}
