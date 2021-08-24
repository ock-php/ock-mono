<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\DecoKey;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\ObCK\Formula\Sequence\Formula_Sequence;
use Donquixote\ObCK\Formula\SequenceVal\Formula_SequenceVal;
use Donquixote\ObCK\Zoo\V2V\Sequence\V2V_Sequence_Decorators;

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
   * Static factory.
   *
   * @param \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface $decorated
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $decorator
   *   Formula for a single decorator.
   */
  public static function createSequence(
    FormulaInterface $decorated,
    FormulaInterface $decorator
  ) {
    return new self(
      $decorated,
      new Formula_SequenceVal(
        new Formula_Sequence($decorator),
        new V2V_Sequence_Decorators()),
      'decorators');
  }

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
