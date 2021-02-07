<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaToAnything;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Exception\SchemaToAnythingException;
use Donquixote\OCUI\Formula\ContextProviding\Formula_ContextProvidingInterface;
use Donquixote\OCUI\Formula\Contextual\Formula_ContextualInterface;
use Donquixote\OCUI\SchemaToAnything\Partial\SchemaToAnythingPartial_SmartChain;
use Donquixote\OCUI\SchemaToAnything\Partial\SchemaToAnythingPartialInterface;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class SchemaToAnything_FromPartial implements SchemaToAnythingInterface {

  /**
   * @var \Donquixote\OCUI\SchemaToAnything\Partial\SchemaToAnythingPartialInterface
   */
  private $partial;

  /**
   * @var \Donquixote\OCUI\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   *
   * @throws \Donquixote\OCUI\Exception\STABuilderException
   */
  public static function create(ParamToValueInterface $paramToValue): self {
    return new self(SchemaToAnythingPartial_SmartChain::create($paramToValue));
  }

  /**
   * @param \Donquixote\OCUI\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[] $partials
   *
   * @return self
   */
  public static function createFromPartials(array $partials): self {
    return new self(new SchemaToAnythingPartial_SmartChain($partials));
  }

  /**
   * @param \Donquixote\OCUI\SchemaToAnything\Partial\SchemaToAnythingPartialInterface $partial
   */
  public function __construct(SchemaToAnythingPartialInterface $partial) {
    $this->partial = $partial;
  }

  /**
   * Immutable setter. Sets a context.
   *
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   *   Context to constrain available options.
   *
   * @return \Donquixote\OCUI\SchemaToAnything\SchemaToAnything_FromPartial
   */
  public function withContext(?CfContextInterface $context) {
    $instance = clone $this;
    $instance->context = $context;
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function schema(FormulaInterface $schema, string $interface): ?object {

    if ($schema instanceof Formula_ContextProvidingInterface) {
      return $this
        ->withContext($schema->getContext())
        ->schema(
          $schema->getDecorated(),
          $interface);
    }

    if ($schema instanceof Formula_ContextualInterface) {
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
