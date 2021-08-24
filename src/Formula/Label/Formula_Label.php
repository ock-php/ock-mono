<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Label;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaBase\Decorator\Formula_DecoratorBase;
use Donquixote\ObCK\Text\TextInterface;

class Formula_Label extends Formula_DecoratorBase implements Formula_LabelInterface {

  /**
   * @var \Donquixote\ObCK\Text\TextInterface|null
   */
  private $label;

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\ObCK\Text\TextInterface|null $label
   */
  public function __construct(FormulaInterface $decorated, ?TextInterface $label) {
    parent::__construct($decorated);
    $this->label = $label;
  }

  /**
   * {@inheritdoc}
   */
  public function getLabel(): ?TextInterface {
    return $this->label;
  }
}
