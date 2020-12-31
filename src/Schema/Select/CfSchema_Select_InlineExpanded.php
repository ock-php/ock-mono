<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Select;

use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownValInterface;
use Donquixote\Cf\Schema\Id\CfSchema_IdInterface;

class CfSchema_Select_InlineExpanded implements CfSchema_SelectInterface {

  /**
   * @var \Donquixote\Cf\Schema\Select\CfSchema_SelectInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\IdToSchema\IdToSchemaInterface
   */
  private $idToSchema;

  /**
   * @param \Donquixote\Cf\Schema\Select\CfSchema_SelectInterface $decorated
   * @param \Donquixote\Cf\IdToSchema\IdToSchemaInterface $idToSchema
   */
  public function __construct(
    CfSchema_SelectInterface $decorated,
    IdToSchemaInterface $idToSchema
  ) {
    $this->decorated = $decorated;
    $this->idToSchema = $idToSchema;
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupedOptions(): array {

    $options = [];
    /** @var string[] $groupOptions */
    foreach ($this->decorated->getGroupedOptions() as $groupLabel => $groupOptions) {
      foreach ($groupOptions as $id => $label) {

        if (NULL === $inlineOptions = $this->idGetInlineOptions($id)) {
          $options[$groupLabel][$id] = $label;
        }
        else {
          foreach ($inlineOptions as $inlineGroupLabel => $inlineGroupOptions) {
            foreach ($inlineGroupOptions as $inlineId => $inlineLabel) {
              $options[$inlineGroupLabel]["$id/$inlineId"] = "$label: $inlineLabel";
            }
          }
          $options[$groupLabel][$id] = "$label - ALL";
        }
      }
    }

    return $options;
  }

  /**
   * @param string $id
   *
   * @return null|string[][]
   */
  private function idGetInlineOptions(string $id): ?array {

    if (NULL === $schema = $this->idGetSelectSchema($id)) {
      return NULL;
    }

    return $schema->getGroupedOptions();
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id) {

    if (FALSE === /* $pos = */ strpos($id, '/')) {
      return $this->decorated->idGetLabel($id);
    }

    [$prefix, $suffix] = explode('/', $id, 2);

    if (NULL === $subSchema = $this->idGetSelectSchema($prefix)) {
      return NULL;
    }

    return $subSchema->idGetLabel($suffix);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($combinedId): bool {

    if (FALSE === /* $pos = */ strpos($combinedId, '/')) {
      return $this->decorated->idIsKnown($combinedId);
    }

    [$prefix, $suffix] = explode('/', $combinedId, 2);

    if (NULL === $subSchema = $this->idGetSelectSchema($prefix)) {
      return FALSE;
    }

    return $subSchema->idIsKnown($suffix);
  }

  /**
   * @param string $id
   *
   * @return \Donquixote\Cf\Schema\Select\CfSchema_SelectInterface|null
   */
  private function idGetSelectSchema($id): ?CfSchema_SelectInterface {

    if (NULL === $idSchema = $this->idGetIdSchema($id)) {
      return NULL;
    }

    if (!$idSchema instanceof CfSchema_SelectInterface) {
      return NULL;
    }

    return $idSchema;
  }

  /**
* @param string $id
   *
   * @return \Donquixote\Cf\Schema\Id\CfSchema_IdInterface|null
   */
  private function idGetIdSchema(string $id): ?CfSchema_IdInterface {

    if (NULL === $nestedSchema = $this->idToSchema->idGetSchema($id)) {
      return NULL;
    }

    if ($nestedSchema instanceof CfSchema_DrilldownInterface) {
      return $nestedSchema->getIdSchema();
    }

    if ($nestedSchema instanceof CfSchema_IdInterface) {
      return $nestedSchema;
    }

    if ($nestedSchema instanceof CfSchema_DrilldownValInterface) {
      return $nestedSchema->getDecorated()->getIdSchema();
    }

    return NULL;

  }
}
