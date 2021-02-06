<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Proxy\Cache;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface Formula_Proxy_CacheInterface extends FormulaInterface {

  /**
   * @return string
   */
  public function getCacheId(): string;

  /**
   * @param mixed $data
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface|null
   */
  public function dataGetSchema($data): ?FormulaInterface;

  /**
   * @return mixed
   */
  public function getData();

}
