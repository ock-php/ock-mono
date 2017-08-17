<?php

namespace Drupal\renderkit8\Schema\Misc\ViewsDisplayCondition;

class ViewsDisplayCondition_Hardcoded implements ViewsDisplayConditionInterface {

  /**
   * @var true[]|null
   */
  private $allowedDisplayTypes;

  /**
   * @var string[]|null
   */
  private $argumentsSignature;

  /**
   * @var bool|null
   */
  private $status = TRUE;

  /**
   * @param string[] $allowedDisplayTypes
   *
   * @return static
   */
  public function withAllowedTypes(array $allowedDisplayTypes) {
    $clone = clone $this;
    $clone->allowedDisplayTypes = array_fill_keys($allowedDisplayTypes, TRUE);
    return $clone;
  }

  /**
   * @param string[]|null $argumentsSignature
   *
   * @return static
   */
  public function withArgumentsSignature(array $argumentsSignature = NULL) {
    $clone = clone $this;
    $clone->argumentsSignature = $argumentsSignature;
    return $clone;
  }

  /**
   * @param bool|null $status
   *
   * @return static
   */
  public function withStatus($status = TRUE) {
    $clone = clone $this;
    $clone->status = $status;
    return $clone;
  }

  /**
   * @param string $id
   * @param array $display
   * @param array|null $default_display
   *
   * @return bool
   */
  public function displayCheckCondition($id, array $display, array $default_display = NULL) {

    if (NULL !== $this->status) {
      if ($display['enabled'] !== $this->status) {
        return FALSE;
      }
    }

    if (NULL !== $this->allowedDisplayTypes) {
      if (!isset($this->allowedDisplayTypes[$display['display_plugin']])) {
        return FALSE;
      }
    }

    if (NULL !== $this->argumentsSignature) {
      if (isset($display['display_options']['arguments'])) {
        $arguments = $display['display_options']['arguments'];
      }
      elseif (isset($default_display['display_options']['arguments'])) {
        $arguments = $default_display['display_options']['arguments'];
      }
      else {
        $arguments = [];
      }

      if (!self::validateArgumentsSignature($arguments, $this->argumentsSignature)) {
        return FALSE;
      }
    }

    return TRUE;
  }

  private static function validateArgumentsSignature(array $arguments, array $signature) {

    if ([] === $signature) {
      return [] === $arguments;
    }

    $argTypes = [];
    foreach ($arguments as $argument) {
      if (isset($argument['validate']['type'])) {
        $argTypes[] = $argument['validate']['type'];
      }
      else {
        $argTypes[] = NULL;
      }
    }

    return $signature === $argTypes;
  }
}
