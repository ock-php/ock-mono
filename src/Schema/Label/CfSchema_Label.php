<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Label;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\SchemaBase\Decorator\CfSchema_DecoratorBase;
use Donquixote\OCUI\Text\TextInterface;

class CfSchema_Label extends CfSchema_DecoratorBase implements CfSchema_LabelInterface {

  /**
   * @var \Donquixote\OCUI\Text\TextInterface|null
   */
  private $label;

  /**
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $decorated
   * @param \Donquixote\OCUI\Text\TextInterface|null $label
   */
  public function __construct(CfSchemaInterface $decorated, ?TextInterface $label) {
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
