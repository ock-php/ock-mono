<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Label;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\SchemaBase\Decorator\Formula_DecoratorBase;
use Donquixote\OCUI\Text\TextInterface;

class Formula_Label extends Formula_DecoratorBase implements Formula_LabelInterface {

  /**
   * @var \Donquixote\OCUI\Text\TextInterface|null
   */
  private $label;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\OCUI\Text\TextInterface|null $label
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
