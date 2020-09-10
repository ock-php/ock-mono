<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\DefaultConf;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

class CfSchema_DefaultConf implements CfSchema_DefaultConfInterface {

  /**
   * @var \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  private $decorated;

  /**
   * @var mixed
   */
  private $defaultConf;

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $decorated
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
