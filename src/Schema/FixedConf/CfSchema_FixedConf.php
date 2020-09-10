<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\FixedConf;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

class CfSchema_FixedConf implements CfSchema_FixedConfInterface {

  /**
   * @var \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $conf;

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $decorated
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
