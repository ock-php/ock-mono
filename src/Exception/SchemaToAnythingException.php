<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Exception;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class SchemaToAnythingException extends \Exception implements UnsupportedSchemaExceptionInterface {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param string $interface
   * @param mixed|null $instead
   *
   * @return self
   */
  public static function createWithInstead(FormulaInterface $schema, string $interface, $instead): self {

    $message = strtr(
      "Failed to create !destination\nfor !schema.",
      [
        '!destination' => $interface,
        '!schema' => \get_class($schema) . ' object',
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
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param string $interface
   * @param string|null $message_append
   *
   * @return self
   */
  public static function create(FormulaInterface $schema, string $interface, ?string $message_append): self {

    $message = strtr(
      "Failed to create !destination\nfor !schema.",
      [
        '!destination' => $interface,
        '!schema' => \get_class($schema) . ' object',
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
