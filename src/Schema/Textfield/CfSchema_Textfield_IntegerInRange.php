<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Textfield;

use Donquixote\Cf\Text\Text;
use Donquixote\Cf\Text\TextInterface;

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
  public function getDescription(): ?TextInterface {

    if (NULL === $this->max) {
      if (NULL === $this->min) {
        return NULL;
      }
      elseif (0 === $this->min) {
        return Text::t('Non-negative integer.');
      }
      elseif (1 === $this->min) {
        return Text::t('Positive integer.');
      }
      else {
        return Text::t('Integer greater or equal to @min', ['@min' => $this->min]);
      }
    }
    elseif (NULL === $this->min) {
      return Text::t('Integer up to @max', ['@max' => $this->max]);
    }
    else {
      return Text::t('Integer in range [@min...@max]', ['@min' => $this->min, '@max' => $this->max]);
    }
  }

  /**
   * @param int $v
   *
   * @return \Donquixote\Cf\Text\TextInterface[]
   */
  protected function numberGetValidationErrors(int $v): array {

    $errors = [];

    if ($v < $this->min) {
      if (0 === $this->min) {
        $errors[] = Text::t('Value must be non-negative.');
      }
      elseif (1 === $this->min) {
        $errors[] = Text::t('Value must be positive.');
      }
      else {
        $errors[] = Text::t(
          'Value must be greater than or equal to @min.',
          ['@min' => $this->min]);
      }
    }

    if ($v > $this->max) {
      $errors[] = Text::t(
        '%name must be no greater than @max.',
        ['@max' => $this->max]);
    }

    return $errors;
  }

}
