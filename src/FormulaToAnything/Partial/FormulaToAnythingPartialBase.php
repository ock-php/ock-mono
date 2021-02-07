<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaToAnything\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

abstract class FormulaToAnythingPartialBase implements FormulaToAnythingPartialInterface {

  /**
   * @var null|string
   */
  private $schemaType;

  /**
   * @var null|string
   */
  private $resultType;

  /**
   * @var int
   */
  private $specifity = 0;

  /**
   * @param string|null $schemaType
   * @param string|null $resultType
   */
  protected function __construct(?string $schemaType, ?string $resultType) {
    $this->schemaType = $schemaType;
    $this->resultType = $resultType;
  }

  /**
   * @param int $specifity
   *
   * @return static
   */
  public function withSpecifity(int $specifity) {

    if ($specifity === $this->specifity) {
      return $this;
    }

    $clone = clone $this;
    $clone->specifity = $specifity;
    return $clone;
  }

  /**
   * @param string $schemaType
   *
   * @return static
   */
  public function withFormulaType(string $schemaType) {

    if ($schemaType === $this->schemaType) {
      return $this;
    }

    $clone = clone $this;
    $clone->schemaType = $schemaType;
    return $clone;
  }

  /**
   * @param string $resultType
   *
   * @return static
   */
  public function withResultType(string $resultType) {

    if ($resultType === $this->resultType) {
      return $this;
    }

    $clone = clone $this;
    $clone->resultType = $resultType;
    return $clone;
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param string $interface
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  final public function schema(
    FormulaInterface $schema,
    string $interface,
    FormulaToAnythingInterface $helper
  ): ?object {

    if (NULL !== $this->schemaType && !$schema instanceof $this->schemaType) {
      return NULL;
    }

    $candidate = $this->schemaDoGetObject($schema, $interface, $helper);

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
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param string $interface
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  abstract protected function schemaDoGetObject(
    FormulaInterface $schema,
    string $interface,
    FormulaToAnythingInterface $helper
  );

  /**
   * {@inheritdoc}
   */
  public function acceptsFormulaClass(string $schemaClass): bool {
    return NULL === $this->schemaType
      || is_a($schemaClass, $this->schemaType, TRUE);
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
