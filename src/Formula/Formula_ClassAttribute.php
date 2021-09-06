<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Formula\StringVal\Formula_StringVal;
use Donquixote\ObCK\Formula\Textfield\Formula_TextfieldBase;
use Donquixote\ObCK\Util\HtmlUtil;
use Donquixote\ObCK\Util\PhpUtil;
use Donquixote\ObCK\Zoo\V2V\String\V2V_StringInterface;

class Formula_ClassAttribute extends Formula_TextfieldBase implements V2V_StringInterface {

  /**
   * @return \Donquixote\ObCK\Formula\StringVal\Formula_StringValInterface
   */
  public static function create() {
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
      }

      $classes[$class] = $class;
    }

    return $errors;
  }

  /**
   * @param string $string
   *
   * @return mixed
   */
  public function stringGetValue(string $string) {

    if ('' === $string) {
      return [];
    }

    // Keep only those class names that don't contain invalid characters, and that are not empty.
    $classes = [];
    foreach (explode(' ', $string) as $class) {
      if ('' !== $class) {
        if (!preg_match('[^A-Za-z0-9_-]', $class)) {
          $classes[] = $class;
        }
      }
    }

    return $classes;
  }

  /**
   * @param string $string
   *
   * @return string
   */
  public function stringGetPhp(string $string): string {

    try {
      $classes = $this->stringGetValue($string);
      return PhpUtil::phpValue($classes);
    }
    catch (\Exception $e) {
      return PhpUtil::exception(\get_class($e), $e->getMessage());
    }
  }
}
