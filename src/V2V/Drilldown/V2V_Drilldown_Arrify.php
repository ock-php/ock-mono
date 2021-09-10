<?php
declare(strict_types=1);

namespace Donquixote\ObCK\V2V\Drilldown;

class V2V_Drilldown_Arrify implements V2V_DrilldownInterface {

  /**
   * @var string
   */
  private $idKey;

  /**
   * @var string
   */
  private $optionsKey;

  /**
   * @param string $idKey
   * @param string $optionsKey
   */
  public function __construct($idKey = 'id', $optionsKey = 'options') {
    $this->idKey = $idKey;
    $this->optionsKey = $optionsKey;
  }

  /**
   * {@inheritdoc}
   */
  final public function idPhpGetPhp($id, string $php) {

    $idKeyPhp = var_export($this->idKey, TRUE);
    $optionsKeyPhp = var_export($this->optionsKey, TRUE);
    $idPhp = var_export($id, TRUE);

    return <<<EOT
[
  $idKeyPhp => $idPhp,
  $optionsKeyPhp => $php,
];
EOT;
  }
}
