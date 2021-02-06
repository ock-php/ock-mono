<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\DefaultConf;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;

class CfSchema_DefaultConf implements CfSchema_DefaultConfInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $defaultConf;

  /**
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $decorated
   * @param mixed $defaultConf
   */
  public function __construct(CfSchemaInterface $decorated, $defaultConf) {
    $this->decorated = $decorated;
    $this->defaultConf = $defaultConf;
  }

  /**
   * {@inheritdoc}
   */
  public function getDecorated(): CfSchemaInterface {
    return $this->decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultConf() {
    return $this->defaultConf;
  }
}
