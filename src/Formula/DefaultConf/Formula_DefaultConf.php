<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\DefaultConf;

use Donquixote\Ock\Core\Formula\FormulaInterface;

class Formula_DefaultConf implements Formula_DefaultConfInterface {

  /**
   * @var \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $defaultConf;

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $decorated
   * @param mixed $defaultConf
   */
  public function __construct(FormulaInterface $decorated, $defaultConf) {
    $this->decorated = $decorated;
    $this->defaultConf = $defaultConf;
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
  public function getDefaultConf() {
    return $this->defaultConf;
  }
}
