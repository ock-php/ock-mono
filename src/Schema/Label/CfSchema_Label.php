<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Label;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBase;
use Donquixote\Cf\Text\TextInterface;

class CfSchema_Label extends CfSchema_DecoratorBase implements CfSchema_LabelInterface {

  /**
   * @var \Donquixote\Cf\Text\TextInterface|null
   */
  private $label;

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $decorated
   * @param \Donquixote\Cf\Text\TextInterface|null $label
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
