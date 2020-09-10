<?php
declare(strict_types=1);

namespace Donquixote\Cf\Exception;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

class SchemaToAnythingException extends \Exception implements UnsupportedSchemaExceptionInterface {

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param mixed|null $instead
   *
   * @return self
   */
  public static function createWithInstead(CfSchemaInterface $schema, $interface, $instead): self {

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
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param string|null $message_append
   *
   * @return self
   */
  public static function create(CfSchemaInterface $schema, string $interface, ?string $message_append): self {

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
