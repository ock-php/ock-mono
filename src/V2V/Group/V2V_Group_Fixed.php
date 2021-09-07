<?php
declare(strict_types=1);

namespace Donquixote\ObCK\V2V\Group;

class V2V_Group_Fixed implements V2V_GroupInterface {

  /**
   * @var string
   */
  private $php;

  /**
   * Constructor.
   *
   * @param string $php
   */
  public function __construct(string $php) {
    $this->php = $php;
  }

  /**
   * {@inheritdoc}
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {
    return $this->php;
  }
}
