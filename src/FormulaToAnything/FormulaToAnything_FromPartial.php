<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaToAnything;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Exception\FormulaToAnythingException;
use Donquixote\OCUI\Formula\ContextProviding\Formula_ContextProvidingInterface;
use Donquixote\OCUI\Formula\Contextual\Formula_ContextualInterface;
use Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartial_SmartChain;
use Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class FormulaToAnything_FromPartial implements FormulaToAnythingInterface {

  /**
   * @var \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface
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
    return new self(FormulaToAnythingPartial_SmartChain::create($paramToValue));
  }

  /**
   * @param \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[] $partials
   *
   * @return self
   */
  public static function createFromPartials(array $partials): self {
    return new self(new FormulaToAnythingPartial_SmartChain($partials));
  }

  /**
   * @param \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface $partial
   */
  public function __construct(FormulaToAnythingPartialInterface $partial) {
    $this->partial = $partial;
  }

  /**
   * Immutable setter. Sets a context.
   *
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   *   Context to constrain available options.
   *
   * @return \Donquixote\OCUI\FormulaToAnything\FormulaToAnything_FromPartial
   */
  public function withContext(?CfContextInterface $context) {
    $instance = clone $this;
    $instance->context = $context;
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function formula(FormulaInterface $formula, string $interface): ?object {

    if ($formula instanceof Formula_ContextProvidingInterface) {
      return $this
        ->withContext($formula->getContext())
        ->formula(
          $formula->getDecorated(),
          $interface);
    }

    if ($formula instanceof Formula_ContextualInterface) {
      return $this
        ->formula(
          $formula->getDecorated($this->context),
          $interface);
    }

    try {
      $candidate = $this->partial->formula($formula, $interface, $this);
    }
    catch (FormulaToAnythingException $e) {
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
