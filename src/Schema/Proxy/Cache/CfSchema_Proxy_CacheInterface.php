<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Proxy\Cache;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

interface CfSchema_Proxy_CacheInterface extends CfSchemaInterface {

  /**
   * @return string
   */
  public function getCacheId(): string;

  /**
   * @param mixed $data
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface|null
   */
  public function dataGetSchema($data): ?CfSchemaInterface;

  /**
   * @return mixed
   */
  public function getData();

}
