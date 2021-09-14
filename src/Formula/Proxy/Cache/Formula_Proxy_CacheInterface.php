<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Proxy\Cache;

use Donquixote\Ock\Core\Formula\FormulaInterface;

interface Formula_Proxy_CacheInterface extends FormulaInterface {

  /**
   * @return string
   */
  public function getCacheId(): string;

  /**
   * @param mixed $data
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   */
  public function dataGetFormula($data): ?FormulaInterface;

  /**
   * @return mixed
   */
  public function getData();

}
