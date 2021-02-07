<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\GroupVal;

use Donquixote\OCUI\Formula\Group\Formula_Group_Empty;
use Donquixote\OCUI\Formula\Group\Formula_GroupInterface;
use Donquixote\OCUI\Formula\ValueProvider\Formula_ValueProviderInterface;
use Donquixote\OCUI\Zoo\V2V\Group\V2V_Group_EmptyWithValueProvider;
use Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface;

class Formula_GroupVal extends Formula_GroupValBase {

  /**
   * @var \Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\OCUI\Formula\ValueProvider\Formula_ValueProviderInterface $valueProvider
   *
   * @return self
   */
  public static function createEmpty(Formula_ValueProviderInterface $valueProvider): Formula_GroupVal {
    return new self(
      new Formula_Group_Empty(),
      new V2V_Group_EmptyWithValueProvider(
        $valueProvider));
  }

  /**
   * @param \Donquixote\OCUI\Formula\Group\Formula_GroupInterface $decorated
   * @param \Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface $v2v
   */
  public function __construct(Formula_GroupInterface $decorated, V2V_GroupInterface $v2v) {
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
