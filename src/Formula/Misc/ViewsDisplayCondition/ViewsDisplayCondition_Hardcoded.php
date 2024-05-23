<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula\Misc\ViewsDisplayCondition;

class ViewsDisplayCondition_Hardcoded implements ViewsDisplayConditionInterface {

  /**
   * @var true[]|null
   */
  private ?array $allowedDisplayTypes;

  /**
   * @var string[]|null
   */
  private ?array $argumentsSignature;

  /**
   * @var bool|null
   */
  private ?bool $status = TRUE;

  /**
   * @param string[] $allowedDisplayTypes
   *
   * @return static
   */
  public function withAllowedTypes(array $allowedDisplayTypes): self {
    $clone = clone $this;
    $clone->allowedDisplayTypes = array_fill_keys($allowedDisplayTypes, TRUE);
    return $clone;
  }

  /**
   * @param string[]|null $argumentsSignature
   *
   * @return static
   */
  public function withArgumentsSignature(array $argumentsSignature = NULL): self {
    $clone = clone $this;
    $clone->argumentsSignature = $argumentsSignature;
    return $clone;
  }

  /**
   * @param bool|null $status
   *
   * @return static
   */
  public function withStatus(?bool $status = TRUE): self {
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
  public function displayCheckCondition(string $id, array $display, array $default_display = NULL): bool {

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

  /**
   * @param mixed[] $arguments
   * @param string[] $signature
   *
   * @return bool
   */
  private static function validateArgumentsSignature(array $arguments, array $signature): bool {

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
