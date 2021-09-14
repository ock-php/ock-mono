<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

abstract class IncarnatorPartialBase implements IncarnatorPartialInterface {

  /**
   * @var null|string
   */
  private $formulaType;

  /**
   * @var null|string
   */
  private $resultType;

  /**
   * @var int
   */
  private $specifity = 0;

  /**
   * @param string|null $formulaType
   * @param string|null $resultType
   */
  protected function __construct(?string $formulaType, ?string $resultType) {
    $this->formulaType = $formulaType;
    $this->resultType = $resultType;
  }

  /**
   * @param int $specifity
   *
   * @return static
   */
  public function withSpecifity(int $specifity): self {

    if ($specifity === $this->specifity) {
      return $this;
    }

    $clone = clone $this;
    $clone->specifity = $specifity;
    return $clone;
  }

  /**
   * @param string $formulaType
   *
   * @return static
   */
  public function withFormulaType(string $formulaType): self {

    if ($formulaType === $this->formulaType) {
      return $this;
    }

    $clone = clone $this;
    $clone->formulaType = $formulaType;
    return $clone;
  }

  /**
   * @param string $resultType
   *
   * @return static
   */
  public function withResultType(string $resultType): self {

    if ($resultType === $this->resultType) {
      return $this;
    }

    $clone = clone $this;
    $clone->resultType = $resultType;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function incarnate(
    FormulaInterface $formula,
    string $interface,
    IncarnatorInterface $incarnator
  ): ?object {

    if (NULL !== $this->formulaType && !$formula instanceof $this->formulaType) {
      return NULL;
    }

    $candidate = $this->formulaDoGetObject($formula, $interface, $incarnator);

    if (NULL === $candidate) {
      return NULL;
    }

    if (!$candidate instanceof $interface) {
      # kdpm($candidate, "Expected $interface, found sth else.");
      # kdpm($this, '$this');
      return NULL;
    }

    return $candidate;
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param string $interface
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  abstract protected function formulaDoGetObject(
    FormulaInterface $formula,
    string $interface,
    IncarnatorInterface $incarnator
  ): ?object;

  /**
   * {@inheritdoc}
   */
  public function acceptsFormulaClass(string $formulaClass): bool {
    return NULL === $this->formulaType
      || is_a($formulaClass, $this->formulaType, TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function providesResultType(string $resultInterface): bool {
    return NULL === $this->resultType
      || is_a($this->resultType, $resultInterface, TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return $this->specifity;
  }

}
