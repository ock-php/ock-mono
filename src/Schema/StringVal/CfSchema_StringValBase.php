<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\StringVal;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface;

abstract class CfSchema_StringValBase implements CfSchema_StringValInterface {

  /**
   * @var \Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface $decorated
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
