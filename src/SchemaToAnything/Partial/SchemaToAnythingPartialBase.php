<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaToAnything\Partial;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;

abstract class SchemaToAnythingPartialBase implements SchemaToAnythingPartialInterface {

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
  public function withSchemaType(string $schemaType) {

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
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  final public function schema(
    CfSchemaInterface $schema,
    string $interface,
    SchemaToAnythingInterface $helper
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
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  abstract protected function schemaDoGetObject(
    CfSchemaInterface $schema,
    string $interface,
    SchemaToAnythingInterface $helper
  );

  /**
   * {@inheritdoc}
   */
  public function acceptsSchemaClass(string $schemaClass): bool {
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
