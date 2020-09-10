<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Group;

use Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface;

abstract class CfSchema_Group_V2VDecoratorBase extends CfSchema_Group_V2VBase {

  /**
   * @var \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface
   */
  private $decoratedSchema;

  /**
   * @var \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface
   */
  private $decoratedV2V;

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $decoratedSchema
   * @param \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface $decoratedV2V
   */
  public function __construct(
    CfSchema_GroupInterface $decoratedSchema,
    V2V_GroupInterface $decoratedV2V
  ) {
    $this->decoratedSchema = $decoratedSchema;
    $this->decoratedV2V = $decoratedV2V;
  }

  /**
   * {@inheritdoc}
   */
  public function getItemSchemas(): array {
    return $this->decoratedSchema->getItemSchemas();
  }

  /**
   * {@inheritdoc}
   */
  public function getLabels(): array {
    return $this->decoratedSchema->getLabels();
  }

  /**
   * {@inheritdoc}
   */
  public function valuesGetValue(array $values) {
    return $this->decoratedV2V->valuesGetValue($values);
  }

  /**
   * {@inheritdoc}
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {
    return $this->decoratedV2V->itemsPhpGetPhp($itemsPhp);
  }

}
