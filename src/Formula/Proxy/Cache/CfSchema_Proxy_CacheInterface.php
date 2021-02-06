<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Proxy\Cache;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;

interface CfSchema_Proxy_CacheInterface extends CfSchemaInterface {

  /**
   * @return string
   */
  public function getCacheId(): string;

  /**
   * @param mixed $data
   *
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface|null
   */
  public function dataGetSchema($data): ?CfSchemaInterface;

  /**
   * @return mixed
   */
  public function getData();

}
