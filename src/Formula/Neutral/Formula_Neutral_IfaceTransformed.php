<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Neutral;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaBase\Decorator\Formula_DecoratorBase;

class Formula_Neutral_IfaceTransformed extends Formula_DecoratorBase implements Formula_NeutralInterface {

  /**
   * @var string
   */
  private $interface;

  /**
   * @var \Donquixote\ObCK\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $decorated
   * @param string $interface
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
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
   * @return \Donquixote\ObCK\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface {
    return $this->context;
  }

}
