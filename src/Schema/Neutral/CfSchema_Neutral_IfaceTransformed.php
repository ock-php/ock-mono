<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Neutral;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBase;

class CfSchema_Neutral_IfaceTransformed extends CfSchema_DecoratorBase implements CfSchema_NeutralInterface {

  /**
   * @var string
   */
  private $interface;

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $decorated
   * @param string $interface
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
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
   * @return \Donquixote\Cf\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface {
    return $this->context;
  }

}
