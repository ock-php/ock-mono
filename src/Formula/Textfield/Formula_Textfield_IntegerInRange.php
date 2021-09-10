<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Textfield;

use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;

class Formula_Textfield_IntegerInRange extends Formula_Textfield_IntegerBase {

  /**
   * @var int|null
   */
  private ?int $min;

  /**
   * @var int|null
   */
  private ?int $max;

  /**
   * @param int|null $min
   * @param int|null $max
   */
  public function __construct(?int $min = NULL, ?int $max = NULL) {
    $this->min = $min;
    $this->max = $max;
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription(): ?TextInterface {

    if (NULL === $this->max) {
      if (NULL === $this->min) {
        return Text::t('Integer number.');
      }

      if (0 === $this->min) {
        return Text::t('Non-negative integer.');
      }

      if (1 === $this->min) {
        return Text::t('Positive integer.');
      }

      return Text::t('Integer greater or equal to @min', [
        '@min' => Text::i($this->min),
      ]);
    }

    if (NULL === $this->min) {
      return Text::t('Integer up to @max', [
        '@max' => Text::i($this->max),
      ]);
    }

    return Text::t('Integer in range [@min...@max]', [
      '@min' => Text::i($this->min),
      '@max' => Text::i($this->max),
    ]);
  }

  /**
   * @param int $number
   *
   * @return \Donquixote\ObCK\Text\TextInterface[]
   */
  protected function numberGetValidationErrors(int $number): array {

    $errors = [];

    if ($this->min !== NULL && $number < $this->min) {
      if (0 === $this->min) {
        $errors[] = Text::t('Value must be non-negative.');
      }
      elseif (1 === $this->min) {
        $errors[] = Text::t('Value must be positive.');
      }
      else {
        $errors[] = Text::t('Value must be greater than or equal to @min.', [
          '@min' => Text::i($this->min),
        ]);
      }
    }

    /**
     * Suppress false positive inspection.
     *
     * See https://github.com/kalessil/phpinspectionsea/issues/1707#issuecomment-917009487.
     *
     * @noinspection InsufficientTypesControlInspection
     */
    if ($this->max !== NULL && $number > $this->max) {
      $errors[] = Text::t('Value must be no greater than @max.', [
        '@max' => Text::i($this->max),
      ]);
    }

    return $errors;
  }

}
