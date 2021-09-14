<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\StringVal;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface;

abstract class Formula_StringValBase implements Formula_StringValInterface {

  /**
   * @var \Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Ock\Formula\Textfield\Formula_TextfieldInterface $decorated
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
