<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToSchema;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;

class IdToSchema_AlwaysTheSame implements IdToSchemaInterface {

  /**
   * @var \Donquixote\OCUI\Formula\ValueProvider\CfSchema_ValueProvider_FixedValue
   */
  private $sameSchema;

  /**
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $sameSchema
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
