<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Formula\DecoKey;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\OCUI\Formula\Sequence\Formula_Sequence;
use Donquixote\OCUI\Formula\SequenceVal\Formula_SequenceVal;
use Donquixote\OCUI\Zoo\V2V\Sequence\V2V_Sequence_Decorators;

class Formula_DecoKey implements FormulaInterface, Formula_DecoKeyInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface
   */
  private FormulaInterface $decorated;

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  private FormulaInterface $decorator;

  /**
   * @var string
   */
  private string $key;

  /**
   * Static factory.
   *
   * @param \Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface $decorated
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $decorator
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
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $decorator
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
