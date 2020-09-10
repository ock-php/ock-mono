<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Textfield;

class CfSchema_Textfield_IntegerInRange extends CfSchema_Textfield_IntegerBase {

  /**
   * @var int|null
   */
  private $min;

  /**
   * @var int|null
   */
  private $max;

  /**
   * @param int|null $min
   * @param int|null $max
   */
  public function __construct($min = NULL, $max = NULL) {
    $this->min = $min;
    $this->max = $max;
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription(): ?string {

    if (NULL === $this->max) {
      if (NULL === $this->min) {
        return NULL;
      }
      elseif (0 === $this->min) {
        return (string)t('Non-negative integer.');
      }
      elseif (1 === $this->min) {
        return (string)t('Positive integer.');
      }
      else {
        return (string)t('Integer greater or equal to @min', ['@min' => $this->min]);
      }
    }
    elseif (NULL === $this->min) {
      return (string)t('Integer up to @max', ['@max' => $this->max]);
    }
    else {
      return (string)t('Integer in range [@min...@max]', ['@min' => $this->min, '@max' => $this->max]);
    }
  }

  /**
   * @param int $v
   *
   * @return string[]
   */
  protected function numberGetValidationErrors(int $v): array {

    $errors = [];

    if ($v < $this->min) {
      if (0 === $this->min) {
        $errors[] = t('Value must be non-negative.');
      }
      elseif (1 === $this->min) {
        $errors[] = t('Value must be positive.');
      }
      else {
        $errors[] = t(
          'Value must be greater than or equal to @min.',
          ['@min' => $this->min]);
      }
    }

    if ($v > $this->max) {
      $errors[] = t(
        '%name must be no greater than @max.',
        ['@max' => $this->max]);
    }

    return $errors;
  }

}
