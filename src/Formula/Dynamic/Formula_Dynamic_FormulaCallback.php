<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Dynamic;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\FormulaException;

/**
 * Formula that depends on other values in the same group.
 *
 * This should only be used internally.
 */
class Formula_Dynamic_FormulaCallback implements Formula_DynamicInterface {

  /**
   * Callback to get the actual formula.
   *
   * @var callable(mixed...): \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  private $formulaCallback;

  /**
   * Constructor.
   *
   * @param list<string> $keys
   * @param callable(mixed...): \Donquixote\Ock\Core\Formula\FormulaInterface $formulaCallback
   */
  public function __construct(
    private readonly array $keys,
    callable $formulaCallback,
  ) {
    $this->formulaCallback = $formulaCallback;
  }

  /**
   * {@inheritdoc}
   */
  public function getKeys(): array {
    return $this->keys;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormula(array $values): FormulaInterface {
    try {
      $formula = ($this->formulaCallback)(...$values);
    }
    catch (\Exception $e) {
      // @todo Export the callback to string.
      throw new FormulaException(
        'Exception in formula callback.',
        0,
        $e,
      );
    }
    if (!$formula instanceof FormulaInterface) {
      // @todo Export the callback to string.
      throw new FormulaException('Callback returned a value that is not a formula.');
    }
    return $formula;
  }

}
