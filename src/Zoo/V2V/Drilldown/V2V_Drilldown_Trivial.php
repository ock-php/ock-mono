<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Zoo\V2V\Drilldown;

class V2V_Drilldown_Trivial implements V2V_DrilldownInterface {

  /**
   * {@inheritdoc}
   */
  public function idPhpGetPhp($id, string $php) {
    return $php;

    // We cannot be sure if $id is multi-line.
    $idSafe = str_replace("\n", '\n', $id);

    return <<<EOT
// Drilldown with \$id = "$idSafe".
$php
EOT;
  }
}
