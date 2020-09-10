<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\V2V\Id;

interface V2V_IdInterface {

  /**
   * @param string|int $id
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function idGetValue($id);

  /**
   * @param string|int $id
   *
   * @return string
   */
  public function idGetPhp($id): string;

}
