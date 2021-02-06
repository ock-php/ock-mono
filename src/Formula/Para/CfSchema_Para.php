<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Para;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;

class CfSchema_Para implements CfSchema_ParaInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  private $paraSchema;

  /**
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $decorated
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $paraSchema
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
