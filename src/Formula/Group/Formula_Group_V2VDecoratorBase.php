<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Group;

use Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface;

abstract class Formula_Group_V2VDecoratorBase extends Formula_Group_V2VBase {

  /**
   * @var \Donquixote\OCUI\Formula\Group\Formula_GroupInterface
   */
  private $decoratedSchema;

  /**
   * @var \Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface
   */
  private $decoratedV2V;

  /**
   * @param \Donquixote\OCUI\Formula\Group\Formula_GroupInterface $decoratedSchema
   * @param \Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface $decoratedV2V
   */
  public function __construct(
    Formula_GroupInterface $decoratedSchema,
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
  public function itemsPhpGetPhp(array $itemsPhp): string {
    return $this->decoratedV2V->itemsPhpGetPhp($itemsPhp);
  }

}
