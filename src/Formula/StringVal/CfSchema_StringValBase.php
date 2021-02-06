<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\StringVal;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Formula\Textfield\CfSchema_TextfieldInterface;

abstract class CfSchema_StringValBase implements CfSchema_StringValInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Textfield\CfSchema_TextfieldInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\OCUI\Formula\Textfield\CfSchema_TextfieldInterface $decorated
   */
  public function __construct(CfSchema_TextfieldInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function getDecorated(): CfSchemaInterface {
    return $this->decorated;
  }
}
