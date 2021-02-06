<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Sequence;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

abstract class CfSchema_SequenceBase implements Formula_SequenceInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  private $itemSchema;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $itemSchema
   */
  public function __construct(FormulaInterface $itemSchema) {
    $this->itemSchema = $itemSchema;
  }

  /**
   * {@inheritdoc}
   */
  public function getItemSchema(): FormulaInterface {
    return $this->itemSchema;
  }
}
