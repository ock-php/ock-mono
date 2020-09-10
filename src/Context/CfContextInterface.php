<?php
declare(strict_types=1);

namespace Donquixote\Cf\Context;

interface CfContextInterface {

  /**
   * @param \ReflectionParameter $param
   *
   * @return bool
   */
  public function paramValueExists(\ReflectionParameter $param);

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
  public function paramNameHasValue($paramName);

  /**
   * @param string $paramName
   *
   * @return mixed
   */
  public function paramNameGetValue($paramName);

  /**
   * @return string
   */
  public function getMachineName();

}
