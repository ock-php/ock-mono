<?php
declare(strict_types=1);

namespace Donquixote\Ock\Exception;

use Donquixote\Ock\Core\Formula\FormulaInterface;

class FormulaToAnythingException extends \Exception implements UnsupportedFormulaExceptionInterface {

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param string $interface
   * @param mixed|null $instead
   *
   * @return self
   */
  public static function createWithInstead(FormulaInterface $formula, string $interface, $instead): self {

    $message = strtr(
      "Failed to create !destination\nfor !formula.",
      [
        '!destination' => $interface,
        '!formula' => \get_class($formula) . ' object',
      ]);

    if (NULL !== $instead) {
      $message .= strtr(
        "\nFound !instead instead.",
        [
          '!instead' => self::formatValue($instead)
        ]);
    }

    return new self($message);
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param string $interface
   * @param string|null $message_append
   *
   * @return self
   */
  public static function create(FormulaInterface $formula, string $interface, ?string $message_append): self {

    $message = strtr(
      "Failed to create !destination\nfor !formula.",
      [
        '!destination' => $interface,
        '!formula' => \get_class($formula) . ' object',
      ]);

    if (NULL !== $message_append) {
      $message .= "\n" . $message_append;
    }

    return new self($message);
  }

  /**
   * @param mixed $value
   *
   * @return string
   */
  private static function formatValue($value): string {

    switch ($type = \gettype($value)) {
      case 'object':
        return \get_class($value) . ' object';
      case 'array':
      case 'resource':
        return $type;
      default:
        return $type . ' (' . var_export($value, TRUE) . ')';
    }
  }

}
