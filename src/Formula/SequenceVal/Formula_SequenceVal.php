<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\SequenceVal;

use Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface;
use Donquixote\OCUI\Zoo\V2V\Sequence\V2V_SequenceInterface;

class Formula_SequenceVal extends Formula_SequenceValBase {

  /**
   * @var \Donquixote\OCUI\Zoo\V2V\Sequence\V2V_SequenceInterface
   */
  private V2V_SequenceInterface $v2v;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface $decorated
   * @param \Donquixote\OCUI\Zoo\V2V\Sequence\V2V_SequenceInterface $v2v
   */
  public function __construct(Formula_SequenceInterface $decorated, V2V_SequenceInterface $v2v) {
    parent::__construct($decorated);
    $this->v2v = $v2v;
  }

  /**
   * {@inheritdoc}
   */
  public function getV2V(): V2V_SequenceInterface {
    return $this->v2v;
  }

}
