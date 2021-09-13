<?php
declare(strict_types=1);

namespace Donquixote\Ock\Incarnator;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\FormulaToAnythingException;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\Util\LocalPackageUtil;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class Incarnator_Chain extends IncarnatorZeroBase {

  /**
   * @var \Donquixote\Ock\Incarnator\IncarnatorInterface[]
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
    $partials = LocalPackageUtil::collectSTAPartials($paramToValue);
    return new self($partials);
  }

  /**
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface[] $partials
   */
  public function __construct(array $partials) {
    $this->partials = $partials;
  }

  /**
   * {@inheritdoc}
   */
  public function breed(
    FormulaInterface $formula,
    string $interface,
    NurseryInterface $nursery
  ): ?object {

    foreach ($this->partials as $mapper) {
      $candidate = $mapper->breed($formula, $interface, $nursery);
      if (NULL !== $candidate) {
        if ($candidate instanceof $interface) {
          return $candidate;
        }
        throw new FormulaToAnythingException('Misbehaving STA.');
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
