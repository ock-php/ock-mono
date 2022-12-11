<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Exception\GeneratorException;
use Donquixote\Ock\Formula\StringVal\Formula_StringVal;
use Donquixote\Ock\Formula\StringVal\Formula_StringValInterface;
use Donquixote\Ock\Formula\Textfield\Formula_TextfieldBase;
use Donquixote\Ock\Util\HtmlUtil;
use Donquixote\Ock\Util\PhpUtil;
use Donquixote\Ock\V2V\String\V2V_StringInterface;

class Formula_ClassAttribute extends Formula_TextfieldBase implements V2V_StringInterface {

  /**
   * @return \Donquixote\Ock\Formula\StringVal\Formula_StringValInterface
   */
  public static function create(): Formula_StringValInterface {
    $formula = new self();
    return new Formula_StringVal($formula, $formula);
  }

  /**
   * @param string $text
   *
   * @return string[]
   */
  public function textGetValidationErrors($text): array {

    if ('' === $text) {
      return [];
    }

    $errors = [];
    $classes = [];
    foreach (explode(' ', $text) as $class) {

      $class_errors = [];
      if ('' === $class) {
        $class_errors[] = 'Class contains more white space than needed.';
      }
      elseif (array_key_exists($class, $classes)) {
        $class_errors[] = 'Found a duplicate class name: :class.';
      }
      elseif (preg_match('@[^A-Za-z0-9_-]@', $class)) {
        $class_errors[] = 'Class :class contains characters that are not allowed in class names.';
      }

      if ([] !== $class_errors) {

        $replacements = [
          ':class' => '<code>' . HtmlUtil::sanitize($text) . '</code>',
        ];

        foreach ($class_errors as $message) {
          $errors[] = t($message, $replacements);
        }

        continue;
      }

      $classes[$class] = $class;
    }

    return $errors;
  }

  /**
   * {@inheritdoc}
   */
  public function stringGetPhp(string $string): string {

    if ('' === $string) {
      return '[]';
    }

    // Keep only those class names that don't contain invalid characters, and that are not empty.
    $classes = [];
    foreach (explode(' ', $string) as $class) {
      if ('' !== $class) {
        if (!\preg_match('@[^A-Za-z0-9_-]@', $class)) {
          $classes[] = $class;
        }
      }
    }

    try {
      return PhpUtil::phpValue($classes);
    }
    catch (\Exception $e) {
      throw new GeneratorException($e->getMessage(), 0, $e);
    }
  }
}
