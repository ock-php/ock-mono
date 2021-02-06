<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Sequence;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;

abstract class CfSchema_SequenceBase implements CfSchema_SequenceInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  private $itemSchema;

  /**
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $itemSchema
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
