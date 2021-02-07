<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Form\Common;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

/**
 * @STA
 */
class FormatorCommon_V2V implements FormulaToAnythingPartialInterface {

  /**
   * {@inheritdoc}
   */
  public function schema(
    FormulaInterface $schema,
    string $interface,
    FormulaToAnythingInterface $helper
  ): ?object {

    if (!$schema instanceof Formula_ValueToValueBaseInterface) {
      return NULL;
    }

    return $helper->schema($schema->getDecorated(), $interface);
  }

  /**
   * {@inheritdoc}
   */
  public function providesResultType(string $resultInterface): bool {
    return is_a(
      $resultInterface ,
      FormatorCommonInterface::class,
      TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function acceptsFormulaClass(string $schemaClass): bool {
    return is_a(
      $schemaClass,
      Formula_ValueToValueBaseInterface::class,
      TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return 0;
  }
}
