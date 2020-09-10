<?php
declare(strict_types=1);

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Emptiness\EmptinessInterface;

interface Evaluator_OptionableInterface extends EvaluatorInterface {

  /**
   * @return \Donquixote\Cf\Emptiness\EmptinessInterface|null
   */
  public function getEmptiness(): ?EmptinessInterface;

}
