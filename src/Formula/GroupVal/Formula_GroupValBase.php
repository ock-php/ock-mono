<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\GroupVal;

use Donquixote\ObCK\Formula\Group\Formula_GroupInterface;

abstract class Formula_GroupValBase implements Formula_GroupValInterface {

  /**
   * @var \Donquixote\ObCK\Formula\Group\Formula_GroupInterface
   */
  private Formula_GroupInterface $decorated;

  /**
   * Same as parent, but must be a group formula.
   *
   * @param \Donquixote\ObCK\Formula\Group\Formula_GroupInterface $decorated
   */
  public function __construct(Formula_GroupInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function getDecorated(): Formula_GroupInterface {
    return $this->decorated;
  }

}
