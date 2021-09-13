<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\Neutral;

use Donquixote\Ock\Context\CfContextInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\FormulaBase\Decorator\Formula_DecoratorBase;

class Formula_Neutral_IfaceTransformed extends Formula_DecoratorBase implements Formula_NeutralInterface {

  /**
   * @var string
   */
  private $interface;

  /**
   * @var \Donquixote\Ock\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $decorated
   * @param string $interface
   * @param \Donquixote\Ock\Context\CfContextInterface|null $context
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
   * @return \Donquixote\Ock\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface {
    return $this->context;
  }

}
