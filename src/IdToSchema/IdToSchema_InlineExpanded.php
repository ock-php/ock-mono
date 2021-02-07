<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToSchema;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\OCUI\Formula\DrilldownVal\Formula_DrilldownValInterface;
use Donquixote\OCUI\Formula\Id\Formula_IdInterface;
use Donquixote\OCUI\Formula\ValueProvider\Formula_ValueProvider_Null;
use Donquixote\OCUI\Formula\ValueToValue\Formula_ValueToValue;
use Donquixote\OCUI\Zoo\V2V\Value\V2V_Value_DrilldownFixedId;

class IdToSchema_InlineExpanded implements IdToSchemaInterface {

  /**
   * @var \Donquixote\OCUI\IdToSchema\IdToSchemaInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\OCUI\IdToSchema\IdToSchemaInterface $decorated
   */
  public function __construct(IdToSchemaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetSchema($combinedId): ?FormulaInterface {

    if (FALSE === /* $pos = */ strpos($combinedId, '/')) {
      return $this->decorated->idGetSchema($combinedId);
    }

    list($prefix, $suffix) = explode('/', $combinedId, 2);

    if (NULL === $nestedSchema = $this->decorated->idGetSchema($prefix)) {
      return NULL;
    }

    if ($nestedSchema instanceof Formula_DrilldownInterface) {
      return $nestedSchema->getIdToSchema()->idGetSchema($suffix);
    }

    if ($nestedSchema instanceof Formula_IdInterface) {
      return new Formula_ValueProvider_Null();
    }

    if ($nestedSchema instanceof Formula_DrilldownValInterface) {
      $deepSchema = $nestedSchema->getDecorated()->getIdToSchema()->idGetSchema($suffix);
      $v2v = new V2V_Value_DrilldownFixedId($nestedSchema->getV2V(), $suffix);
      return new Formula_ValueToValue($deepSchema, $v2v);
    }

    return NULL;
  }
}
