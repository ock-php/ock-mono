<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToSchema;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class IdToSchema_AlwaysTheSame implements IdToSchemaInterface {

  /**
   * @var \Donquixote\OCUI\Formula\ValueProvider\Formula_ValueProvider_FixedValue
   */
  private $sameSchema;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $sameSchema
   */
  public function __construct(FormulaInterface $sameSchema) {
    $this->sameSchema = $sameSchema;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetSchema($id): ?FormulaInterface {
    return $this->sameSchema;
  }
}
