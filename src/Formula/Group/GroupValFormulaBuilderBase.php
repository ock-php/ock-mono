<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Group;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupVal;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\Ock\Util\PhpUtil;
use Donquixote\Ock\V2V\Group\V2V_Group_Call;
use Donquixote\Ock\V2V\Group\V2V_Group_Fixed;
use Donquixote\Ock\V2V\Group\V2V_Group_ObjectMethodCall;
use Donquixote\Ock\V2V\Group\V2V_Group_PhpPlaceholders;
use Donquixote\Ock\V2V\Group\V2V_Group_Rekey;
use Donquixote\Ock\V2V\Group\V2V_Group_Trivial;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;

abstract class GroupValFormulaBuilderBase {

  /**
   * @param string $key
   * @param callable $callback
   * @param list<string> $keys
   *
   * @return \Donquixote\Ock\Formula\Group\GroupValFormulaBuilder
   */
  public function addCall(string $key, callable $callback, array $keys = []): GroupValFormulaBuilder {
    return $this->addDependentVal(
      $key,
      V2V_Group_Call::fromCallable($callback),
      $keys,
    );
  }

  /**
   * @param string $key
   * @param string $objectKey
   * @param string $method
   * @param array $paramKeys
   *
   * @return \Donquixote\Ock\Formula\Group\GroupValFormulaBuilder
   */
  public function addObjectMethodCall(string $key, string $objectKey, string $method, array $paramKeys): GroupValFormulaBuilder {
    return $this->addDependentVal(
      $key,
      new V2V_Group_ObjectMethodCall($objectKey, $method, $paramKeys),
    );
  }

  /**
   * @param string $key
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
   * @param list<string>|null $keys
   *
   * @return \Donquixote\Ock\Formula\Group\GroupValFormulaBuilder
   */
  public function addDependentVal(string $key, V2V_GroupInterface $v2v, array $keys = NULL): GroupValFormulaBuilder {
    if ($keys !== NULL) {
      $v2v = new V2V_Group_Rekey($v2v, $keys);
    }
    return $this->addPartial($key, $v2v);
  }

  /**
   * @param string $key
   * @param string $phpWithPlaceholders
   *
   * @return \Donquixote\Ock\Formula\Group\GroupValFormulaBuilder
   */
  public function addExpression(string $key, string $phpWithPlaceholders): GroupValFormulaBuilder {
    return $this->addDependentVal(
      $key,
      new V2V_Group_PhpPlaceholders($phpWithPlaceholders),
    );
  }

  /**
   * Adds an optionless group option.
   *
   * @param string $key
   * @param mixed $value
   *
   * @return \Donquixote\Ock\Formula\Group\GroupValFormulaBuilder
   *
   * @throws \Exception
   *   Value is not supported for export.
   */
  public function addValue(string $key, mixed $value): GroupValFormulaBuilder {
    return $this->addValuePhp($key, PhpUtil::phpValue($value));
  }

  /**
   * Adds an optionless group option.
   *
   * @param string $key
   * @param mixed $value
   *
   * @return \Donquixote\Ock\Formula\Group\GroupValFormulaBuilder
   */
  public function addScalar(string $key, string|int|bool|float $value): GroupValFormulaBuilder {
    return $this->addValuePhp($key, PhpUtil::phpValueSimple($value));
  }

  /**
   * @param string $key
   * @param callable $callback
   *
   * @return \Donquixote\Ock\Formula\Group\GroupValFormulaBuilder
   */
  public function addValueCall(string $key, callable $callback): GroupValFormulaBuilder {
    return $this->addValuePhp($key, PhpUtil::phpCall($callback));
  }

  /**
   * @param string $key
   * @param string $php
   *
   * @return \Donquixote\Ock\Formula\Group\GroupValFormulaBuilder
   */
  public function addValuePhp(string $key, string $php): GroupValFormulaBuilder {
    return $this->addDependentVal($key, new V2V_Group_Fixed($php));
  }

  /**
   * Constructs a class with group options as parameters.
   *
   * @param string $class
   * @param string[]|null $keys
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public function construct(string $class, array $keys = NULL): FormulaInterface {
    return $this->val(V2V_Group_Call::fromClass($class), $keys);
  }

  /**
   * Endpoint. Calls a factory callback with the group options as parameters.
   *
   * @param callable $callback
   * @param string[]|null $keys
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public function call(callable $callback, array $keys = NULL): FormulaInterface {
    return $this->val(V2V_Group_Call::fromCallable($callback), $keys);
  }

  /**
   * Endpoint. Calls an object method.
   *
   * @param string $objectKey
   * @param string $method
   * @param array $paramKeys
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public function callObjectMethod(string $objectKey, string $method, array $paramKeys): FormulaInterface {
    return $this->val(new V2V_Group_ObjectMethodCall(
      $objectKey,
      $method,
      $paramKeys,
    ));
  }

  /**
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
   * @param string[]|null $keys
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public function val(V2V_GroupInterface $v2v, array $keys = NULL): FormulaInterface {
    return $this->buildGroupValFormula($v2v, $keys);
  }

  /**
   * @param string $phpWithPlaceholders
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public function buildPhp(string $phpWithPlaceholders): FormulaInterface {
    return $this->buildGroupValFormula(
      new V2V_Group_PhpPlaceholders($phpWithPlaceholders),
    );
  }

  /**
   * @param class-string $class
   * @param array $phpArgsWithPlaceholders
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public function constructPhp(string $class, array $phpArgsWithPlaceholders): FormulaInterface {
    return $this->buildPhp(PhpUtil::phpNewClass(
      $class,
      $phpArgsWithPlaceholders,
    ));
  }

  /**
   * @param callable $callback
   * @param array $phpArgsWithPlaceholders
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public function callPhp(callable $callback, array $phpArgsWithPlaceholders): FormulaInterface {
    return $this->buildPhp(PhpUtil::phpCall(
      $callback,
      $phpArgsWithPlaceholders,
    ));
  }

  /**
   * @param string[] $keys
   *
   * @return \Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface
   */
  public function buildRekeyed(array $keys): Formula_GroupValInterface {
    return $this->buildGroupValFormula(NULL, $keys);
  }

  /**
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface|null $v2v
   * @param string[]|null $keys
   *
   * @return \Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface
   */
  public function buildGroupValFormula(V2V_GroupInterface $v2v = NULL, array $keys = NULL): Formula_GroupValInterface {
    $formula = $this->getGroupFormula();
    $v2v = $this->decorateV2V($v2v);
    if ($keys) {
      $v2v = new V2V_Group_Rekey(
        $v2v ?? new V2V_Group_Trivial(),
        $keys,
      );
    }
    if ($v2v) {
      $formula = new Formula_GroupVal($formula, $v2v);
    }
    return $formula;
  }

  /**
   * @param callable $getPhp
   * @param array|null $keys
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public function generate(callable $getPhp, array $keys = NULL): FormulaInterface {
    return $this->buildGroupValFormula(
      new class ($getPhp) implements V2V_GroupInterface {
        public function __construct(private $getPhp) {}
        public function itemsPhpGetPhp(array $itemsPhp): string {
          return ($this->getPhp)($itemsPhp);
        }
      },
      $keys,
    );
  }

  /**
   * @param string $key
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
   *
   * @return \Donquixote\Ock\Formula\Group\GroupValFormulaBuilder
   */
  abstract protected function addPartial(string $key, V2V_GroupInterface $v2v): GroupValFormulaBuilder;

  /**
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface|null $v2v
   *
   * @return \Donquixote\Ock\V2V\Group\V2V_GroupInterface|null
   */
  abstract protected function decorateV2V(?V2V_GroupInterface $v2v): ?V2V_GroupInterface;

  /**
   * @return \Donquixote\Ock\Formula\Group\Formula_GroupInterface
   */
  abstract protected function getGroupFormula(): Formula_GroupInterface;

}
