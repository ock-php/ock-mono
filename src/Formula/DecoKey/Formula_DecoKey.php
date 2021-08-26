<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\DecoKey;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface;

class Formula_DecoKey implements FormulaInterface, Formula_DecoKeyInterface {

  /**
   * @var \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface
   */
  private FormulaInterface $decorated;

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  private FormulaInterface $decorator;

  /**
   * @var string
   */
  private string $key;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $decorator
   * @param string $key
   */
  public function __construct(
    FormulaInterface $decorated,
    FormulaInterface $decorator,
    string $key
  ) {
    $this->decorated = $decorated;
    $this->decorator = $decorator;
    $this->key = $key;
  }

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
