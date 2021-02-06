<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\DefaultConf;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class CfSchema_DefaultConf implements Formula_DefaultConfInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $defaultConf;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $decorated
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
