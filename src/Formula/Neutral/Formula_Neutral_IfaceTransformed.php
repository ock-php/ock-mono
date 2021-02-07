<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Neutral;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\SchemaBase\Decorator\Formula_DecoratorBase;

class Formula_Neutral_IfaceTransformed extends Formula_DecoratorBase implements Formula_NeutralInterface {

  /**
   * @var string
   */
  private $interface;

  /**
   * @var \Donquixote\OCUI\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $decorated
   * @param string $interface
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   */
  public function __construct(
    FormulaInterface $decorated,
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
