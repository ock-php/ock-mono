<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\MoreArgs;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface;
use Donquixote\Ock\FormulaBase\Decorator\Formula_DecoratorBase;

class Formula_MoreArgs extends Formula_DecoratorBase implements Formula_MoreArgsInterface {

  /**
   * @var \Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface[]
   */
  private $more;

  /**
   * @var int|string
   */
  private $specialKey;

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $decorated
   * @param string|int $specialKey
   *
   * @return \Donquixote\Ock\Formula\MoreArgs\Formula_MoreArgs
   */
  public function createEmpty(FormulaInterface $decorated, $specialKey = 0): Formula_MoreArgs {
    return new self($decorated, [], $specialKey);
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface[] $more
   * @param string|int $specialKey
   */
  public function __construct(FormulaInterface $decorated, array $more = [], $specialKey = 0) {
    parent::__construct($decorated);
    $this->more = $more;
    $this->specialKey = $specialKey;
  }

  /**
   * @param string $key
   * @param \Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface $formula
   *
   * @return \Donquixote\Ock\Formula\MoreArgs\Formula_MoreArgs
   */
  public function withItemFormula(string $key, Formula_OptionlessInterface $formula): Formula_MoreArgs {
    $clone = clone $this;
    $clone->more[$key] = $formula;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function getMoreArgs(): array {
    return $this->more;
  }

  /**
   * {@inheritdoc}
   */
  public function getSpecialKey(): string {
    return $this->specialKey;
  }

}
