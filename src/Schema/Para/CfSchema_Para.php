<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Para;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;

class CfSchema_Para implements CfSchema_ParaInterface {

  /**
   * @var \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  private $paraSchema;

  /**
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $decorated
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $paraSchema
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
