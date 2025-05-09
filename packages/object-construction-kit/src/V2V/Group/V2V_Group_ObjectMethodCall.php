<?php

declare(strict_types=1);

namespace Ock\Ock\V2V\Group;

use Ock\CodegenTools\Util\CodeGen;

class V2V_Group_ObjectMethodCall implements V2V_GroupInterface {

  /**
   * Constructor.
   *
   * @param string $objectKey
   * @param string $method
   * @param list<string> $paramKeys
   */
  public function __construct(
    private readonly string $objectKey,
    private readonly string $method,
    private readonly array $paramKeys,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function itemsPhpGetPhp(array $itemsPhp, array $conf): string {
    $paramsPhp = [];
    foreach ($this->paramKeys as $paramPos => $sourceKey) {
      $paramsPhp[$paramPos] = $itemsPhp[$sourceKey];
    }
    return CodeGen::phpCallMethod($itemsPhp[$this->objectKey], $this->method, $paramsPhp);
  }

}
