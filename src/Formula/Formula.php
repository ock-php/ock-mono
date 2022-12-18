<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Group\GroupFormulaBuilder;
use Donquixote\Ock\Formula\Iface\Formula_Iface;
use Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface;
use Donquixote\Ock\Formula\Select\Flat\FlatSelectBuilderInterface;
use Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelect_Fixed;
use Donquixote\Ock\Formula\Sequence\Formula_Sequence;
use Donquixote\Ock\Formula\ValueProvider\Formula_FixedPhp;
use Donquixote\Ock\Util\PhpUtil;
use Donquixote\Ock\Util\UtilBase;

final class Formula extends UtilBase {

  /**
   * Validates one or more formulas.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface ...$formulas
   *   Formulas to validate.
   *   In PHP < 8.0, string keys are not allowed here.
   */
  public static function validate(FormulaInterface ...$formulas) {}

  /**
   * Validates an array of formulas.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface[] $formulas
   *   Array of formulas to validate.
   *   This array can have string keys.
   */
  public static function validateMultiple(array $formulas) {
    self::validate(...array_values($formulas));
  }

  /**
   * Starts a builder for a select formula.
   *
   * @return \Donquixote\Ock\Formula\Select\Flat\FlatSelectBuilderInterface
   */
  public static function flatSelect(): FlatSelectBuilderInterface {
    return new Formula_FlatSelect_Fixed([]);
  }

  /**
   * Creates a formula where the value is an interface instance.
   *
   * @param string $interface
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function iface(string $interface): FormulaInterface {
    return new Formula_Iface($interface);
  }

  /**
   * Creates a formula where the value is an interface instance or NULL.
   *
   * @param string $interface
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function ifaceOrNull(string $interface): FormulaInterface {
    return new Formula_Iface($interface, TRUE);
  }

  /**
   * Creates a formula where the value is an array of interface instances.
   *
   * @param string $interface
   *
   * @return \Donquixote\Ock\Formula\Sequence\Formula_Sequence
   */
  public static function ifaceSequence(string $interface): Formula_Sequence {
    return new Formula_Sequence(
      new Formula_Iface($interface));
  }

  /**
   * Starts a fluent builder for a group formula.
   *
   * @return \Donquixote\Ock\Formula\Group\GroupFormulaBuilder
   */
  public static function group(): GroupFormulaBuilder {
    return new GroupFormulaBuilder();
  }

  /**
   * Converts a formula to a less abstract version.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public static function replace(
    FormulaInterface $formula,
    UniversalAdapterInterface $universalAdapter,
  ): ?FormulaInterface {
    return $universalAdapter->adapt($formula, FormulaInterface::class);
  }

  /**
   * @param mixed $value
   *
   * @return \Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface
   *
   * @throws \Exception
   *   Value cannot be exported.
   */
  public static function value(mixed $value): Formula_OptionlessInterface {
    return self::valuePhp(PhpUtil::phpValue($value));
  }

  /**
   * @param mixed $value
   *
   * @return \Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface
   */
  public static function valueSimple(mixed $value): Formula_OptionlessInterface {
    return self::valuePhp(PhpUtil::phpValueSimple($value));
  }

  /**
   * @param mixed $php
   *
   * @return \Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface
   */
  public static function valuePhp(mixed $php): Formula_OptionlessInterface {
    return new Formula_FixedPhp($php);
  }

  /**
   * @param string $serviceId
   *
   * @return \Donquixote\Ock\Formula\ValueProvider\Formula_FixedPhp
   */
  public static function serviceExpression(string $serviceId): Formula_FixedPhp {
    return new Formula_FixedPhp(PhpUtil::phpCallMethod(
      '$container',
      'get',
      [var_export($serviceId, TRUE)],
    ));
  }

  /**
   * @param string $serviceId
   * @param string $method
   * @param array $args
   *
   * @return \Donquixote\Ock\Formula\ValueProvider\Formula_FixedPhp
   */
  public static function serviceMethodCallExpression(string $serviceId, string $method, array $args): Formula_FixedPhp {
    return new Formula_FixedPhp(PhpUtil::phpCallMethod(
      '$container',
      'get',
      [var_export($serviceId, TRUE)],
    ));
  }

}
