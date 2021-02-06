<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToSchema;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\OCUI\Schema\DrilldownVal\CfSchema_DrilldownValInterface;
use Donquixote\OCUI\Schema\Id\CfSchema_IdInterface;
use Donquixote\OCUI\Schema\ValueProvider\CfSchema_ValueProvider_Null;
use Donquixote\OCUI\Schema\ValueToValue\CfSchema_ValueToValue;
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
  public function idGetSchema($combinedId): ?CfSchemaInterface {

    if (FALSE === /* $pos = */ strpos($combinedId, '/')) {
      return $this->decorated->idGetSchema($combinedId);
    }

    list($prefix, $suffix) = explode('/', $combinedId, 2);

    if (NULL === $nestedSchema = $this->decorated->idGetSchema($prefix)) {
      return NULL;
    }

    if ($nestedSchema instanceof CfSchema_DrilldownInterface) {
      return $nestedSchema->getIdToSchema()->idGetSchema($suffix);
    }

    if ($nestedSchema instanceof CfSchema_IdInterface) {
      return new CfSchema_ValueProvider_Null();
    }

    if ($nestedSchema instanceof CfSchema_DrilldownValInterface) {
      $deepSchema = $nestedSchema->getDecorated()->getIdToSchema()->idGetSchema($suffix);
      $v2v = new V2V_Value_DrilldownFixedId($nestedSchema->getV2V(), $suffix);
      return new CfSchema_ValueToValue($deepSchema, $v2v);
    }

    return NULL;
  }
}
