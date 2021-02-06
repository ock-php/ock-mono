<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\StringVal;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Textfield\Formula_TextfieldInterface;

abstract class CfSchema_StringValBase implements CfSchema_StringValInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Textfield\Formula_TextfieldInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\OCUI\Formula\Textfield\Formula_TextfieldInterface $decorated
   */
  public function __construct(Formula_TextfieldInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function getDecorated(): FormulaInterface {
    return $this->decorated;
  }
}
