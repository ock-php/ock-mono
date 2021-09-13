<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\MultiStep;

use Donquixote\Ock\Core\Formula\FormulaInterface;

class Formula_MultiStep_Callback extends Formula_MultiStepBase {

  /**
   * @var callable
   */
  private $callback;

  /**
   * Constructor.
   *
   * @param string $key
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param callable $next
   */
  public function __construct(string $key, FormulaInterface $formula, callable $next) {
    parent::__construct($key, $formula);
    $this->callback = $next;
  }

  /**
   * {@inheritdoc}
   */
  public function next($conf): ?Formula_MultiStepInterface {
    return ($this->callback)($conf);
  }

}
