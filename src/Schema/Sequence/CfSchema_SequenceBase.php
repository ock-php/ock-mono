<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Sequence;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

abstract class CfSchema_SequenceBase implements CfSchema_SequenceInterface {

  /**
   * @var \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  private $itemSchema;

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $itemSchema
   */
  public function __construct(CfSchemaInterface $itemSchema) {
    $this->itemSchema = $itemSchema;
  }

  /**
   * {@inheritdoc}
   */
  public function getItemSchema(): CfSchemaInterface {
    return $this->itemSchema;
  }
}
