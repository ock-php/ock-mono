<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Label;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBase;

class CfSchema_Label extends CfSchema_DecoratorBase implements CfSchema_LabelInterface {

  /**
   * @var null|string
   */
  private $label;

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $decorated
   * @param string|null $label
   */
  public function __construct(CfSchemaInterface $decorated, $label) {
    parent::__construct($decorated);
    $this->label = $label;
  }

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->label;
  }
}
