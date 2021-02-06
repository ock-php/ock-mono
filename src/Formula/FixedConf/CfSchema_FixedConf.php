<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\FixedConf;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;

class CfSchema_FixedConf implements CfSchema_FixedConfInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $conf;

  /**
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $decorated
   * @param mixed $conf
   */
  public function __construct(CfSchemaInterface $decorated, $conf) {
    $this->decorated = $decorated;
    $this->conf = $conf;
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
  public function getConf() {
    return $this->conf;
  }
}
