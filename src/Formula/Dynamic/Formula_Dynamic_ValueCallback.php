<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Dynamic;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\FormulaException;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\ValueProvider\Formula_ValueProvider_FixedPhp;

/**
 * Formula that depends on other values in the same group.
 *
 * This should only be used internally.
 */
class Formula_Dynamic_ValueCallback implements Formula_DynamicInterface {

  /**
   * Callback to get the actual formula.
   *
   * @var callable(mixed...): \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  private $valueCallback;

  /**
   * Constructor.
   *
   * @param list<string> $keys
   * @param callable(mixed...): \Donquixote\Ock\Core\Formula\FormulaInterface $valueCallback
   */
  public function __construct(
    private readonly array $keys,
    callable $valueCallback,
  ) {
    $this->valueCallback = $valueCallback;
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
      $value = ($this->valueCallback)(...$values);
    }
    catch (\Exception $e) {
      // @todo Export the callback to string.
      throw new FormulaException(
        'Exception in value callback.',
        0,
        $e,
      );
    }
    try {
      return Formula::value($value);
    }
    catch (\Exception $e) {
      // @todo Export the callback to string.
      throw new FormulaException(
        'Callback returned a value that is not exportable to PHP.',
        0,
        $e,
      );
    }
  }

}
