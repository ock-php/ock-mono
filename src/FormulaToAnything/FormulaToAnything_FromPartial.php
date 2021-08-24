<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaToAnything;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Exception\FormulaToAnythingException;
use Donquixote\ObCK\Formula\ContextProviding\Formula_ContextProvidingInterface;
use Donquixote\ObCK\Formula\Contextual\Formula_ContextualInterface;
use Donquixote\ObCK\FormulaToAnything\Partial\FormulaToAnythingPartial_SmartChain;
use Donquixote\ObCK\FormulaToAnything\Partial\FormulaToAnythingPartialInterface;
use Donquixote\ObCK\Util\MessageUtil;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class FormulaToAnything_FromPartial implements FormulaToAnythingInterface {

  /**
   * @var \Donquixote\ObCK\FormulaToAnything\Partial\FormulaToAnythingPartialInterface
   */
  private $partial;

  /**
   * @var \Donquixote\ObCK\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   *
   * @throws \Donquixote\ObCK\Exception\STABuilderException
   */
  public static function create(ParamToValueInterface $paramToValue): self {
    return new self(FormulaToAnythingPartial_SmartChain::create($paramToValue));
  }

  /**
   * @param \Donquixote\ObCK\FormulaToAnything\Partial\FormulaToAnythingPartialInterface[] $partials
   *
   * @return self
   */
  public static function createFromPartials(array $partials): self {
    return new self(new FormulaToAnythingPartial_SmartChain($partials));
  }

  /**
   * @param \Donquixote\ObCK\FormulaToAnything\Partial\FormulaToAnythingPartialInterface $partial
   */
  public function __construct(FormulaToAnythingPartialInterface $partial) {
    $this->partial = $partial;
  }

  /**
   * Immutable setter. Sets a context.
   *
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
   *   Context to constrain available options.
   *
   * @return static
   */
  public function withContext(?CfContextInterface $context): self {
    $instance = clone $this;
    $instance->context = $context;
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function formula(FormulaInterface $formula, string $interface): object {

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

    $candidate = $this->partial->formula($formula, $interface, $this);

    if ($candidate instanceof $interface) {
      return $candidate;
    }

    $replacements = [
      '@formula_class' => get_class($formula),
      '@interface' => $interface,
      '@found' => MessageUtil::formatValue($candidate),
    ];

    if ($candidate === NULL) {
      throw new FormulaToAnythingException(strtr(
        'Unsupported formula of class @formula_class: Expected @interface object, found @found.',
        $replacements));
    }

    throw new \RuntimeException(strtr(
      'Misbehaving FTA for formula of class @formula_class: Expected @interface object, found @found.',
      $replacements));
  }
}
