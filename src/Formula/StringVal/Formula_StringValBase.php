<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\StringVal;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Textfield\Formula_TextfieldInterface;

abstract class Formula_StringValBase implements Formula_StringValInterface {

  /**
   * @var \Donquixote\ObCK\Formula\Textfield\Formula_TextfieldInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\ObCK\Formula\Textfield\Formula_TextfieldInterface $decorated
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
