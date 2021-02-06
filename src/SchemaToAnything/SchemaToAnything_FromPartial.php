<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaToAnything;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Exception\SchemaToAnythingException;
use Donquixote\Cf\Schema\ContextProviding\CfSchema_ContextProvidingInterface;
use Donquixote\Cf\Schema\Contextual\CfSchema_ContextualInterface;
use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartial_SmartChain;
use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class SchemaToAnything_FromPartial implements SchemaToAnythingInterface {

  /**
   * @var \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface
   */
  private $partial;

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|null
   */
  private $context;

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
   * Immutable setter. Sets a context.
   *
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *   Context to constrain available options.
   *
   * @return \Donquixote\Cf\SchemaToAnything\SchemaToAnything_FromPartial
   */
  public function withContext(?CfContextInterface $context) {
    $instance = clone $this;
    $instance->context = $context;
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function schema(CfSchemaInterface $schema, string $interface): ?object {

    if ($schema instanceof CfSchema_ContextProvidingInterface) {
      return $this
        ->withContext($schema->getContext())
        ->schema(
          $schema->getDecorated(),
          $interface);
    }

    if ($schema instanceof CfSchema_ContextualInterface) {
      return $this
        ->schema(
          $schema->getDecorated($this->context),
          $interface);
    }

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
