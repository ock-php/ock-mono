<?php
declare(strict_types=1);

namespace Donquixote\Cf\Generator;

use Donquixote\Cf\Emptiness\EmptinessInterface;

interface Generator_OptionableInterface extends GeneratorInterface {

  /**
   * @return \Donquixote\Cf\Emptiness\EmptinessInterface|null
   */
  public function getEmptiness(): ?EmptinessInterface;

}
