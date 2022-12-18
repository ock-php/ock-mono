<?php

declare(strict_types=1);

namespace Donquixote\Ock\V2V\Drilldown;

class V2V_Drilldown_Merge implements V2V_DrilldownInterface {

  /**
   * @param string $idKey
   */
  public function __construct(
    private readonly string $idKey = 'id',
  ) {}

  /**
   * {@inheritdoc}
   */
  final public function idPhpGetPhp(int|string $id, string $php): string {

    $idKeyPhp = var_export($this->idKey, TRUE);
    $idPhp = var_export($id, TRUE);

    return <<<EOT
[$idKeyPhp => $idPhp]
  + $php;
EOT;
  }

}
