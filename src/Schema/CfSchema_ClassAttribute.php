<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\StringVal\CfSchema_StringVal;
use Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldBase;
use Donquixote\Cf\Util\HtmlUtil;
use Donquixote\Cf\Util\PhpUtil;
use Donquixote\Cf\Zoo\V2V\String\V2V_StringInterface;

class CfSchema_ClassAttribute extends CfSchema_TextfieldBase implements V2V_StringInterface {

  /**
   * @return \Donquixote\Cf\Schema\StringVal\CfSchema_StringValInterface
   */
  public static function create() {
    $schema = new self();
    return new CfSchema_StringVal($schema, $schema);
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
   * @param string|int $string
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function stringGetValue($string) {

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
   * @param string|int $string
   *
   * @return string
   */
  public function stringGetPhp($string): string {

    try {
      $classes = $this->stringGetValue($string);
      return PhpUtil::phpValue($classes);
    }
    catch (\Exception $e) {
      return PhpUtil::exception(\get_class($e), $e->getMessage());
    }
  }
}
