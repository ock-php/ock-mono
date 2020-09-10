<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\GroupVal;

use Donquixote\Cf\Schema\Group\CfSchema_Group_Empty;
use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProviderInterface;
use Donquixote\Cf\Zoo\V2V\Group\V2V_Group_EmptyWithValueProvider;
use Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface;

class CfSchema_GroupVal extends CfSchema_GroupValBase {

  /**
   * @var \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProviderInterface $valueProvider
   *
   * @return self
   */
  public static function createEmpty(CfSchema_ValueProviderInterface $valueProvider): CfSchema_GroupVal {
    return new self(
      new CfSchema_Group_Empty(),
      new V2V_Group_EmptyWithValueProvider(
        $valueProvider));
  }

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $decorated
   * @param \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface $v2v
   */
  public function __construct(CfSchema_GroupInterface $decorated, V2V_GroupInterface $v2v) {
    parent::__construct($decorated);
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function getV2V(): V2V_GroupInterface {
    return $this->v2v;
  }
}
