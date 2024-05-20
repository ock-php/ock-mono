<?php

declare(strict_types = 1);

namespace Ock\CodegenTools\Util;

class PhpUtil {

  /**
   * Checks if a given type name is built-in.
   *
   * @param string $type
   *   The type name.
   *
   * @return bool
   *   TRUE for e.g. 'string', 'int' etc, FALSE for class names.
   */
  public static function typeIsBuiltin(string $type): bool {
    static $types_map = [
      // These values are not safe with the detection mechanism.
      'null' => TRUE,
      'false' => TRUE,
      'true' => TRUE,
      // The 'resource' type will be interpreted as a class.
      'resource' => FALSE,
      // Add some known types to speed things up.
      'int' => TRUE,
      'bool' => TRUE,
      'float' => TRUE,
      'string' => TRUE,
      'array' => TRUE,
    ];
    return $types_map[$type]
      ??= $types_map[strtolower($type)]
      ??= self::determineIfTypeIsBuiltin($type);
  }

  /**
   * Checks if a given type name is built-in.
   *
   * @param string $type
   *   The type name.
   *
   * @return bool
   *   TRUE for e.g. 'string', 'int' etc, FALSE for class names.
   */
  private static function determineIfTypeIsBuiltin(string $type): bool {
    if (!preg_match('@^[a-zA-Z]+$@', $type)) {
      // The type name is not safe to use in eval().
      return FALSE;
    }
    $eval = "return static function (): $type {};";
    $f = eval($eval);
    try {
      $rf = new \ReflectionFunction($f);
    }
    catch (\ReflectionException) {
      return FALSE;
    }
    $rt = $rf->getReturnType();
    if (!$rt) {
      return FALSE;
    }
    return $rt->isBuiltin();
  }

  /**
   * Prepends a 'return ' to a PHP value expression.
   *
   * Comments above the expression are moved above the return statement.
   *
   * @param string $expression
   *  PHP value expression.
   *
   * @return string
   *   PHP statement with added 'return ' statement.
   */
  public static function buildReturnStatement(string $expression): string {
    $php_full = '<?php ' . $expression;
    $tokens = token_get_all($php_full);
    $prefix = '';
    foreach ($tokens as $token) {
      if (is_string($token)) {
        break;
      }
      switch ($token[0]) {
        case \T_WHITESPACE:
        case \T_COMMENT:
        case \T_DOC_COMMENT:
        case \T_OPEN_TAG:
          break;

        default:
          break 2;
      }
      $prefix .= $token[1];
    }
    return substr($prefix, 6)
      . 'return '
      . substr($php_full, strlen($prefix))
      . ';';
  }

  /**
   * @param string $adapteePhp
   *   Php adaptee expresssion.
   * @param string $adapterPhp
   *   Php expression that contains a placeholder for a adaptee expression.
   *
   * @return string
   */
  public static function phpAdapt(string $adapteePhp, string $adapterPhp): string {
    return str_replace(
      self::phpPlaceholderAdaptee(),
      $adapteePhp,
      $adapterPhp,
    );
  }

  /**
   * @param string $decoratedPhp
   *   Php decorated expresssion.
   * @param string $decoratorPhp
   *   Php expression that contains a placeholder for a decorated expression.
   *
   * @return string
   */
  public static function phpDecorate(string $decoratedPhp, string $decoratorPhp): string {
    return str_replace(
      self::phpPlaceholderDecorated(),
      $decoratedPhp,
      $decoratorPhp,
    );
  }

  /**
   * @return string
   */
  public static function phpPlaceholderDecorated(): string {
    return CodeGen::phpCallStatic([Expr::class, 'plDecorated']);
  }

  /**
   * @param int|string $key
   *
   * @return string
   */
  public static function phpPlaceholder(int|string $key): string {
    return CodeGen::phpCallStatic([Expr::class, 'pl'], [
      \var_export($key, TRUE),
    ]);
  }

  /**
   * @return string
   */
  public static function phpPlaceholderAdaptee(): string {
    return CodeGen::phpCallStatic([Expr::class, 'plAdaptee']);
  }

}
