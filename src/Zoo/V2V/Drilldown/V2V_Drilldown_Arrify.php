<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\V2V\Drilldown;

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
  public function idValueGetValue($id, $value) {

    return [
      $this->idKey => $id,
      $this->optionsKey => $value,
    ];
  }

  /**
   * @param string|int $id
   * @param string $php
   *
   * @return mixed
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
