<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\DecoKey;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface;

class Formula_DecoKey implements FormulaInterface, Formula_DecoKeyInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface $decorated
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $decorator
   * @param string $key
   */
  public function __construct(
    private readonly Formula_DrilldownInterface $decorated,
    private readonly FormulaInterface $decorator,
    private readonly string $key,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getDecorated(): Formula_DrilldownInterface {
    return $this->decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function getDecoratorFormula(): FormulaInterface {
    return $this->decorator;
  }

  /**
   * {@inheritdoc}
   */
  public function getDecoKey(): string {
    return $this->key;
  }

}
