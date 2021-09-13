<?php
declare(strict_types=1);

namespace Donquixote\Ock\Context;

interface CfContextInterface {

  /**
   * @param \ReflectionParameter $param
   *
   * @return bool
   */
  public function paramValueExists(\ReflectionParameter $param): bool;

  /**
   * @param \ReflectionParameter $param
   *
   * @return mixed
   */
  public function paramGetValue(\ReflectionParameter $param);

  /**
   * @param string $paramName
   *
   * @return bool
   */
  public function paramNameHasValue(string $paramName): bool;

  /**
   * @param string $paramName
   *
   * @return mixed
   */
  public function paramNameGetValue(string $paramName);

  /**
   * @return string
   */
  public function getMachineName(): string;

}
