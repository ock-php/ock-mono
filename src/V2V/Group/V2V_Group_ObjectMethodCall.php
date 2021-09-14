<?php

declare(strict_types=1);

namespace Donquixote\Ock\V2V\Group;

use Donquixote\Ock\Util\PhpUtil;

class V2V_Group_ObjectMethodCall implements V2V_GroupInterface {

  private string $objectKey;

  private string $method;

  private array $paramKeys;

  /**
   * Constructor.
   *
   * @param string $objectKey
   * @param string $method
   * @param array $paramKeys
   */
  public function __construct(string $objectKey, string $method, array $paramKeys) {
    $this->objectKey = $objectKey;
    $this->method = $method;
    $this->paramKeys = $paramKeys;
  }

  /**
   * {@inheritdoc}
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {
    $paramsPhp = [];
    foreach ($this->paramKeys as $paramPos => $sourceKey) {
      $paramsPhp[$paramPos] = $itemsPhp[$sourceKey];
    }
    return PhpUtil::phpCallMethod($itemsPhp[$this->objectKey], $this->method, $paramsPhp);
  }

}
