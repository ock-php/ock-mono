<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\DefaultConf;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

class Formula_DefaultConf implements Formula_DefaultConfInterface {

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $defaultConf;

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $decorated
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
