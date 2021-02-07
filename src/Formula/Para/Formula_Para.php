<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Para;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class Formula_Para implements Formula_ParaInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  private $paraSchema;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $decorated
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $paraSchema
   */
  public function __construct(FormulaInterface $decorated, FormulaInterface $paraSchema) {
    $this->decorated = $decorated;
    $this->paraSchema = $paraSchema;
  }

  /**
   * {@inheritdoc}
   */
  public function getDecorated(): FormulaInterface {
    return $this->decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function getParaSchema(): FormulaInterface {
    return $this->paraSchema;
  }
}
