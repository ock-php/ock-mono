<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Discovery\FactoryToSTA\FactoryToSTAInterface;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Util\LocalPackageUtil;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class IncarnatorPartial_Chain extends SpecificAdapterZeroBase {

  /**
   * @var \Donquixote\Ock\IncarnatorPartial\SpecificAdapterInterface[]
   */
  private $partials;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   *
   * @throws \Donquixote\Ock\Exception\STABuilderException
   */
  public static function create(ParamToValueInterface $paramToValue): self {
    $partials = LocalPackageUtil::collectIncarnators($paramToValue);
    return new self($partials);
  }

  /**
   * @param \Donquixote\Ock\IncarnatorPartial\SpecificAdapterInterface[] $partials
   */
  public function __construct(array $partials) {
    $this->partials = $partials;
  }

  /**
   * {@inheritdoc}
   */
  public function incarnate(
    FormulaInterface $formula,
    string $interface,
    UniversalAdapterInterface $universalAdapter
  ): ?object {

    foreach ($this->partials as $mapper) {
      $candidate = $mapper->incarnate($formula, $interface, $universalAdapter);
      if (NULL !== $candidate) {
        if ($candidate instanceof $interface) {
          return $candidate;
        }
        throw new IncarnatorException('Misbehaving STA.');
      }
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function providesResultType(string $resultInterface): bool {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function acceptsFormulaClass(string $formulaClass): bool {
    return TRUE;
  }

}
