<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Nursery\Cradle;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Nursery\NurseryInterface;
use Donquixote\ObCK\Util\LocalPackageUtil;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class Cradle_Chain extends CradleZeroBase {

  /**
   * @var \Donquixote\ObCK\Nursery\Cradle\CradleInterface[]
   */
  private $partials;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   *
   * @throws \Donquixote\ObCK\Exception\STABuilderException
   */
  public static function create(ParamToValueInterface $paramToValue): self {
    $partials = LocalPackageUtil::collectSTAPartials($paramToValue);
    return new self($partials);
  }

  /**
   * @param \Donquixote\ObCK\Nursery\Cradle\CradleInterface[] $partials
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
