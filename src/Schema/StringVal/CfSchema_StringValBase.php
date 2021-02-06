<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\StringVal;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Schema\Textfield\CfSchema_TextfieldInterface;

abstract class CfSchema_StringValBase implements CfSchema_StringValInterface {

  /**
   * @var \Donquixote\OCUI\Schema\Textfield\CfSchema_TextfieldInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\OCUI\Schema\Textfield\CfSchema_TextfieldInterface $decorated
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
