<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\V2V\Drilldown;

use Donquixote\Cf\Exception\EvaluatorException;

class V2V_Drilldown_Merge implements V2V_DrilldownInterface {

  /**
   * @var string
   */
  private $idKey;

  /**
   * @param string $idKey
   */
  public function __construct($idKey = 'id') {
    $this->idKey = $idKey;
  }

  /**
   * {@inheritdoc}
   */
  public function idValueGetValue($id, $value) {

    if (!\is_array($value)) {
      throw new EvaluatorException("Drilldown value is expected to be an array.");
    }

    return [$this->idKey => $id] + $value;
  }

  /**
   * @param string|int $id
   * @param string $php
   *
   * @return mixed
   */
  final public function idPhpGetPhp($id, string $php) {

    $idKeyPhp = var_export($this->idKey, TRUE);
    $idPhp = var_export($id, TRUE);

    return <<<EOT
[$idKeyPhp => $idPhp]
  + $php;
EOT;
  }
}
