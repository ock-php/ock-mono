<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaToAnything\Partial;

use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\LocalPackageUtil;

class SchemaToAnythingPartial_Chain implements SchemaToAnythingPartialInterface {

  /**
   * @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  private $partials;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   *
   * @throws \Donquixote\Cf\Exception\STABuilderException
   */
  public static function create(ParamToValueInterface $paramToValue): self {
    $partials = LocalPackageUtil::collectSTAPartials($paramToValue);
    return new self($partials);
  }

  /**
   * @param \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[] $partials
   */
  public function __construct(array $partials) {
    $this->partials = $partials;
  }

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   *
   */
  public function schema(
    CfSchemaInterface $schema,
    string $interface,
    SchemaToAnythingInterface $helper
  ) {

    foreach ($this->partials as $mapper) {
      $candidate = $mapper->schema($schema, $interface, $helper);
      if (NULL !== $candidate) {
        if ($candidate instanceof $interface) {
          return $candidate;
        }
      }
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function providesResultType($resultInterface): bool {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function acceptsSchemaClass($schemaClass): bool {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return 0;
  }
}
