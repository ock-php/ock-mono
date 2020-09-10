<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Para;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

class CfSchema_Para implements CfSchema_ParaInterface {

  /**
   * @var \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  private $paraSchema;

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $decorated
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $paraSchema
   */
  public function __construct(CfSchemaInterface $decorated, CfSchemaInterface $paraSchema) {
    $this->decorated = $decorated;
    $this->paraSchema = $paraSchema;
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
  public function getParaSchema(): CfSchemaInterface {
    return $this->paraSchema;
  }
}
