<?php
declare(strict_types=1);

namespace Donquixote\Ock\Incarnator;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\IncarnatorPartial\IncarnatorPartial_SmartChain;
use Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface;
use Donquixote\Ock\Util\MessageUtil;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class Incarnator_FromPartial extends IncarnatorBase {

  /**
   * @var \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface
   */
  private $partial;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   * @param string $cache_id
   *
   * @return self
   *
   * @throws \Donquixote\Ock\Exception\STABuilderException
   */
  public static function create(ParamToValueInterface $paramToValue, string $cache_id): self {
    return new self(IncarnatorPartial_SmartChain::create($paramToValue), $cache_id);
  }

  /**
   * @param \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[] $partials
   * @param string $cache_id
   *
   * @return self
   */
  public static function createFromPartials(array $partials, string $cache_id): self {
    return new self(new IncarnatorPartial_SmartChain($partials), $cache_id);
  }

  /**
   * @param \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface $partial
   * @param string $cache_id
   */
  public function __construct(IncarnatorPartialInterface $partial, string $cache_id) {
    $this->partial = $partial;
    parent::__construct($cache_id);
  }

  /**
   * {@inheritdoc}
   */
  public function incarnate(FormulaInterface $formula, string $interface, IncarnatorInterface $incarnator): object {

    $candidate = $this->partial->incarnate($formula, $interface, $incarnator);

    if ($candidate instanceof $interface) {
      return $candidate;
    }

    $replacements = [
      '@formula_class' => get_class($formula),
      '@interface' => $interface,
      '@found' => MessageUtil::formatValue($candidate),
    ];

    if ($candidate === NULL) {
      throw new IncarnatorException(strtr(
        'Unsupported formula of class @formula_class: Expected @interface object, found @found.',
        $replacements));
    }

    throw new \RuntimeException(strtr(
      'Misbehaving FTA for formula of class @formula_class: Expected @interface object, found @found.',
      $replacements));
  }
}
