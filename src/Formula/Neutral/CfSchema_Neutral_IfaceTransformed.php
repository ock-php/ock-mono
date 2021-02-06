<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Neutral;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\SchemaBase\Decorator\CfSchema_DecoratorBase;

class CfSchema_Neutral_IfaceTransformed extends CfSchema_DecoratorBase implements CfSchema_NeutralInterface {

  /**
   * @var string
   */
  private $interface;

  /**
   * @var \Donquixote\OCUI\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $decorated
   * @param string $interface
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   */
  public function __construct(
    CfSchemaInterface $decorated,
    string $interface,
    CfContextInterface $context = NULL
  ) {
    parent::__construct($decorated);
    $this->interface = $interface;
    $this->context = $context;
  }

  /**
   * @return string
   */
  public function getInterface(): string {
    return $this->interface;
  }

  /**
   * @return \Donquixote\OCUI\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface {
    return $this->context;
  }

}
