<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\FixedConf;

use Donquixote\Ock\Core\Formula\FormulaInterface;

class Formula_FixedConf implements Formula_FixedConfInterface {

  /**
   * @var \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $conf;

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $decorated
   * @param mixed $conf
   */
  public function __construct(FormulaInterface $decorated, $conf) {
    $this->decorated = $decorated;
    $this->conf = $conf;
  }

  /**
   * {@inheritdoc}
   */
  public function getDecorated(): FormulaInterface {
    return $this->decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function getConf() {
    return $this->conf;
  }
}
