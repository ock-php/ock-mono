<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaToAnything;

use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Exception\SchemaToAnythingException;
use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartial_SmartChain;
use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface;

class SchemaToAnything_FromPartial implements SchemaToAnythingInterface {

  /**
   * @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface
   */
  private $partial;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   *
   * @throws \Donquixote\Cf\Exception\STABuilderException
   */
  public static function create(ParamToValueInterface $paramToValue): self {
    return new self(SchemaToAnythingPartial_SmartChain::create($paramToValue));
  }

  /**
   * @param \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[] $partials
   *
   * @return self
   */
  public static function createFromPartials(array $partials): self {
    return new self(new SchemaToAnythingPartial_SmartChain($partials));
  }

  /**
   * @param \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface $partial
   */
  public function __construct(SchemaToAnythingPartialInterface $partial) {
    $this->partial = $partial;
  }

  /**
   * {@inheritdoc}
   */
  public function schema(CfSchemaInterface $schema, string $interface): ?object {

    try {
      $candidate = $this->partial->schema($schema, $interface, $this);
    }
    catch (SchemaToAnythingException $e) {
      // @todo Log this!
      unset($e);
      return NULL;
    }

    if ($candidate instanceof $interface) {
      return $candidate;
    }

    if (NULL === $candidate) {
      return NULL;
    }

    // @todo Log this!
    return NULL;
  }
}
